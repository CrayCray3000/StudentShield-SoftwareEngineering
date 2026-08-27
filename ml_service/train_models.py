import os
import evaluate
import numpy as np
from collections import Counter
from datasets import load_dataset
from transformers import AutoTokenizer, AutoModelForSequenceClassification, TrainingArguments, Trainer, EarlyStoppingCallback
import torch
from torch import nn

# Suppress HuggingFace warnings for cleaner console output
os.environ["HTTP_PROXY"] = ""
os.environ["HTTPS_PROXY"] = ""

# Setting up metric evaluation (Precision, Recall, F1)
# evaluate library will calculate all the mathematical classification scores
clf_metrics = evaluate.combine(["accuracy", "f1", "precision", "recall"])

def compute_metrics(eval_pred):
    predictions, labels = eval_pred
    predictions = np.argmax(predictions, axis=1)
    return clf_metrics.compute(predictions=predictions, references=labels)


# ── Custom Trainer with Class-Weighted Loss ──────────────────────────
# Your dataset is IMBALANCED (more bullying samples than neutral).
# A standard Trainer treats every sample equally, so the model learns
# to just predict the majority class.  This subclass injects inverse-
# frequency weights into CrossEntropyLoss so the model pays MORE
# attention to the minority class → higher overall accuracy.
class WeightedTrainer(Trainer):
    def __init__(self, class_weights=None, **kwargs):
        super().__init__(**kwargs)
        if class_weights is not None:
            self.class_weights = torch.tensor(class_weights, dtype=torch.float32)
        else:
            self.class_weights = None

    def compute_loss(self, model, inputs, return_outputs=False, **kwargs):
        labels = inputs.pop("labels")
        outputs = model(**inputs)
        logits = outputs.logits
        if self.class_weights is not None:
            # Cast to logits.dtype to avoid Half vs Float mismatch errors
            weight = self.class_weights.to(device=logits.device, dtype=logits.dtype)
            loss_fn = nn.CrossEntropyLoss(weight=weight)
        else:
            loss_fn = nn.CrossEntropyLoss()
        loss = loss_fn(logits, labels)
        return (loss, outputs) if return_outputs else loss


def fine_tune_model(model_name, output_dir, dataset):
    print(f"\n{'='*50}")
    print(f"Starting Fine-Tuning Pipeline for: {model_name}")
    print(f"{'='*50}")

    # 1. Load Tokenizer & Model
    tokenizer = AutoTokenizer.from_pretrained(model_name)
    model = AutoModelForSequenceClassification.from_pretrained(model_name, num_labels=2)
    
    # 2. Tokenize Dataset
    def tokenize_function(examples):
        # 128 tokens captures full Taglish context without wasting GPU memory
        return tokenizer(examples["text"], padding="max_length", truncation=True, max_length=256)
        
    tokenized_datasets = dataset.map(tokenize_function, batched=True)
    
    # Pre-split subsets from generate_dataset.py
    train_dataset = tokenized_datasets["train"]
    eval_dataset = tokenized_datasets["validation"]

    # ── Compute inverse-frequency class weights ──────────────────────
    label_counts = Counter(train_dataset["label"])
    total = sum(label_counts.values())
    num_classes = len(label_counts)
    # Formula: weight_i = total / (num_classes * count_i)
    class_weights = [total / (num_classes * label_counts[i]) for i in range(num_classes)]
    print(f"  Class distribution: {dict(label_counts)}")
    print(f"  Computed class weights: {[f'{w:.3f}' for w in class_weights]}")

    # 3. Hyperparameter Configuration (Maximum Accuracy Settings)
    # mDeBERTa-v3-base is ~280M params (~5x heavier than DistilBERT).
    # On a T4 GPU (16GB VRAM) we MUST reduce per-device batch size to 4.
    # We compensate with gradient_accumulation_steps=4 so the effective
    # batch size is still 16, keeping training stable without OOM crashes.
    # Determine correct evaluation strategy parameter name for installed transformers version
    import transformers
    from packaging import version
    tf_version = version.parse(transformers.__version__)
    eval_key = "eval_strategy" if tf_version >= version.parse("4.41.0") else "evaluation_strategy"

    kwargs = {
        "output_dir": output_dir,
        "learning_rate": 3e-6,
        "per_device_train_batch_size": 4,
        "per_device_eval_batch_size": 4,
        "gradient_accumulation_steps": 4,
        "num_train_epochs": 15,
        "weight_decay": 0.01,
        "warmup_steps": 50,            # Universal parameter supported across all transformers versions
        "lr_scheduler_type": "cosine",
        "adam_epsilon": 1e-6,
        "max_grad_norm": 1.0,
        "fp16": False,
        eval_key: "epoch",
        "save_strategy": "epoch",
        "load_best_model_at_end": True,
        "metric_for_best_model": "f1",
        "greater_is_better": True,
        "logging_steps": 10,
        "use_cpu": not torch.cuda.is_available(),
        "report_to": "none",
        "dataloader_num_workers": 0
    }
    training_args = TrainingArguments(**kwargs)

    # 4. Trainer Initialization (with class-weighted loss)
    trainer = WeightedTrainer(
        class_weights=class_weights,
        model=model,
        args=training_args,
        train_dataset=train_dataset,
        eval_dataset=eval_dataset,
        compute_metrics=compute_metrics,
        callbacks=[EarlyStoppingCallback(early_stopping_patience=3)]
    )

    # 5. Training Loop
    print(f"\nExecuting Training Sequence for {model_name}...")
    trainer.train()
    
    # 6. Final Evaluation (Precision, Recall, F1)
    print(f"\nEvaluating Final {model_name} against Baseline Validation Set...")
    eval_results = trainer.evaluate()
    print(f"\nEvaluation Results for {model_name}:")
    for key, value in eval_results.items():
        if isinstance(value, float):
            print(f"  {key}: {value:.4f}")
        else:
            print(f"  {key}: {value}")
        
    # 7. Model Persistence
    print(f"Writing Fine-Tuned Weights and Tokenizer Config to {output_dir}")
    trainer.save_model(output_dir)
    tokenizer.save_pretrained(output_dir)
    
    return eval_results

if __name__ == "__main__":
    train_file = "data/merged_train.csv" if os.path.exists("data/merged_train.csv") else "data/train_vents.csv"
    val_file = "data/merged_val.csv" if os.path.exists("data/merged_val.csv") else "data/val_vents.csv"

    if not os.path.exists(train_file) or not os.path.exists(val_file):
        print("Error: Training data CSVs not found in data/ folder.")
        exit(1)
        
    print(f"Loading training data: {train_file} and validation data: {val_file}...")
    raw_dataset = load_dataset('csv', data_files={'train': train_file, 'validation': val_file})
    
    # Run mDeBERTa Primary Model Fine-Tuning
    mdeberta_metrics = fine_tune_model("microsoft/mdeberta-v3-base", "./models/mdeberta_finetuned", raw_dataset)
    
    # RoBERTa baseline comparison disabled to avoid Colab disk space overflow.
    # print("\n" + "="*50)
    # print("FINAL FINE-TUNING EVALUATION SUMMARY:")
    # print("="*50)
