import os
import evaluate
import numpy as np
from datasets import load_dataset
from transformers import AutoTokenizer, AutoModelForSequenceClassification, TrainingArguments, Trainer
import torch

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

def fine_tune_model(model_name, output_dir, dataset):
    print(f"\n{'='*50}")
    print(f"Starting Fine-Tuning Pipeline for: {model_name}")
    print(f"{'='*50}")

    # 1. Load Tokenizer & Model
    tokenizer = AutoTokenizer.from_pretrained(model_name)
    model = AutoModelForSequenceClassification.from_pretrained(model_name, num_labels=2)
    
    # 2. Tokenize Dataset
    def tokenize_function(examples):
        return tokenizer(examples["text"], padding="max_length", truncation=True, max_length=512)
        
    tokenized_datasets = dataset.map(tokenize_function, batched=True)
    
    # Pre-split subsets from generate_dataset.py
    train_dataset = tokenized_datasets["train"]
    eval_dataset = tokenized_datasets["validation"]

    # 3. Hyperparameter Configuration
    # We use max_steps=4 strictly to circumvent hardware timeouts on non-GPU CPU hosts.
    # In a full production cloud slice, remove max_steps and rely on num_train_epochs=3.
    training_args = TrainingArguments(
        output_dir=output_dir,
        learning_rate=3e-5,
        per_device_train_batch_size=8,
        per_device_eval_batch_size=8,
        num_train_epochs=5,
        weight_decay=0.01,
        eval_strategy="epoch",
        save_strategy="epoch",
        load_best_model_at_end=True,
        # Hardware Configuration: Full Epoch Deep Learning Enabled
        logging_steps=10,
        use_cpu=not torch.cuda.is_available() # Fallback safely to CPU if no CUDA GPU exists
    )

    # 4. Trainer Initialization
    trainer = Trainer(
        model=model,
        args=training_args,
        train_dataset=train_dataset,
        eval_dataset=eval_dataset,
        compute_metrics=compute_metrics,
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
    if not os.path.exists("data/train_vents.csv") or not os.path.exists("data/val_vents.csv"):
        print("Error: train_vents.csv or val_vents.csv not found. Please run generate_dataset.py first.")
        exit(1)
        
    print("Loading Leak-free Augmented Synthetic Dataset...")
    # Load raw CSV datasets exported from the internal generation script safely split beforehand
    raw_dataset = load_dataset('csv', data_files={'train': 'data/train_vents.csv', 'validation': 'data/val_vents.csv'})
    
    # Run mDeBERTa Primary Model Fine-Tuning
    mdeberta_metrics = fine_tune_model("microsoft/mdeberta-v3-base", "./models/mdeberta_finetuned", raw_dataset)
    
    # RoBERTa baseline comparison disabled to avoid Colab disk space overflow.
    # print("\n" + "="*50)
    # print("FINAL FINE-TUNING EVALUATION SUMMARY:")
    # print("="*50)
