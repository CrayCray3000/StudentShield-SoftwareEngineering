import sys
import os
import csv
import time

# Ensure current directory is in sys.path
current_dir = os.path.dirname(os.path.abspath(__file__))
sys.path.append(current_dir)

# Set the required environment variable
os.environ["ML_API_KEY"] = "svbsm-production-ml-key-v1"

from main import analyze_text_sync

def evaluate():
    csv_path = os.path.join(current_dir, "data", "val_vents.csv")
    if not os.path.exists(csv_path):
        print(f"Error: Validation dataset not found at {csv_path}")
        return

    print("Loading models and initializing evaluation...")
    start_time = time.time()
    
    # Run a dummy analysis to trigger model loading before timing individual inferences
    analyze_text_sync("warmup text", student_id=None)
    print("Models loaded successfully. Starting evaluation on val_vents.csv...")

    tp = 0
    tn = 0
    fp = 0
    fn = 0
    
    total_processed = 0
    mismatches = []

    with open(csv_path, mode="r", encoding="utf-8") as f:
        reader = csv.DictReader(f)
        rows = list(reader)
        total_rows = len(rows)
        print(f"Total validation samples: {total_rows}")

        for idx, row in enumerate(rows):
            text = row["text"]
            actual = int(row["label"])
            
            # Predict
            res = analyze_text_sync(text, student_id=None)
            risk_level = res.get("risk_level", "LOW")
            pred = 1 if risk_level in ["MEDIUM", "HIGH", "CRITICAL"] else 0
            
            if actual == 1 and pred == 1:
                tp += 1
            elif actual == 0 and pred == 0:
                tn += 1
            elif actual == 0 and pred == 1:
                fp += 1
                mismatches.append({"text": text, "actual": actual, "pred_level": risk_level, "pred": pred})
            elif actual == 1 and pred == 0:
                fn += 1
                mismatches.append({"text": text, "actual": actual, "pred_level": risk_level, "pred": pred})
                
            total_processed += 1
            if total_processed % 50 == 0 or total_processed == total_rows:
                print(f"Processed {total_processed}/{total_rows} samples...")

    elapsed = time.time() - start_time
    avg_latency = (elapsed / total_rows) * 1000 if total_rows > 0 else 0

    # Calculate metrics
    accuracy = (tp + tn) / total_processed if total_processed > 0 else 0
    precision = tp / (tp + fp) if (tp + fp) > 0 else 0
    recall = tp / (tp + fn) if (tp + fn) > 0 else 0
    f1 = 2 * (precision * recall) / (precision + recall) if (precision + recall) > 0 else 0

    report = []
    report.append("==================================================")
    report.append("       STUDENTSHIELD ML MODEL VALIDATION REPORT    ")
    report.append("==================================================")
    report.append(f"Validation File: data/val_vents.csv")
    report.append(f"Total Samples Evaluated: {total_processed}")
    report.append(f"Total Execution Time: {elapsed:.2f} seconds")
    report.append(f"Average Inference Latency: {avg_latency:.2f} ms/sample")
    report.append("--------------------------------------------------")
    report.append("CLASSIFICATION METRICS:")
    report.append(f"Accuracy  : {accuracy:.4%}")
    report.append(f"Precision : {precision:.4%}")
    report.append(f"Recall    : {recall:.4%}")
    report.append(f"F1-Score  : {f1:.4%}")
    report.append("--------------------------------------------------")
    report.append("CONFUSION MATRIX:")
    report.append("                     Predicted LOW (0)   Predicted Distress/Abuse (1)")
    report.append(f"Actual Normal (0)    {tn:<19} {fp:<28}")
    report.append(f"Actual Distress (1)  {fn:<19} {tp:<28}")
    report.append("==================================================")

    report_str = "\n".join(report)
    print(report_str)

    # Save report to artifacts / ml_service directory
    report_path = os.path.join(current_dir, "validation_metrics_report.txt")
    with open(report_path, "w", encoding="utf-8") as rf:
        rf.write(report_str)
    print(f"Report saved to {report_path}")

    # Also save a sample of mismatches to help debug or explain limitations
    if mismatches:
        mismatch_path = os.path.join(current_dir, "validation_mismatches.txt")
        with open(mismatch_path, "w", encoding="utf-8") as mf:
            mf.write("==================================================\n")
            mf.write("              MODEL MISMATCH ANALYSES             \n")
            mf.write("==================================================\n\n")
            for i, mis in enumerate(mismatches[:30]):  # Save top 30 mismatches
                mf.write(f"Mismatch #{i+1}:\n")
                mf.write(f"Text  : {mis['text']}\n")
                mf.write(f"Actual: {mis['actual']} | Predicted: {mis['pred']} ({mis['pred_level']})\n")
                mf.write("-" * 50 + "\n")
        print(f"Mismatches log saved to {mismatch_path}")

if __name__ == "__main__":
    evaluate()
