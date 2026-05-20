# StudentShield Metric System
### Complete Documentation of the AI Risk Classification & Evaluation Framework

---

## 1. Overview

StudentShield uses a **multi-layered metric system** that operates across two domains:

| Domain | Purpose |
|--------|---------|
| **ML Model Evaluation Metrics** | Measures how well the AI models perform during training (Accuracy, Precision, Recall, F1-Score) |
| **Runtime Risk Classification Metrics** | Determines student risk levels in real-time using a 9-stage NLP pipeline |

---

## 2. ML Model Evaluation Metrics (Training Phase)

These metrics are computed during model fine-tuning in `train_models.py` using the HuggingFace `evaluate` library:

```python
clf_metrics = evaluate.combine(["accuracy", "f1", "precision", "recall"])
```

| Metric | Formula | What It Measures | Why It Matters for StudentShield |
|--------|---------|------------------|----------------------------------|
| **Accuracy** | `(TP + TN) / (TP + TN + FP + FN)` | Overall correct predictions out of total predictions | General performance gauge — are we classifying vents correctly overall? |
| **Precision** | `TP / (TP + FP)` | Of all "distress" predictions, how many were actually distress? | Prevents **false alarms** — avoids sending unnecessary SMS alerts to parents |
| **Recall** | `TP / (TP + FN)` | Of all actual distress cases, how many did the model catch? | Prevents **missed crises** — ensures no genuine suicidal student goes undetected |
| **F1-Score** | `2 × (Precision × Recall) / (Precision + Recall)` | Harmonic mean balancing Precision and Recall | The **primary metric** — balances false alarms vs. missed crises |

> **Key**: TP = True Positive, TN = True Negative, FP = False Positive, FN = False Negative

### 2.1 Evaluation Dataset

| Parameter | Value |
|-----------|-------|
| Dataset | `student_vents.csv` (1,000 synthetic samples) |
| Split Ratio | 80% Training / 20% Validation |
| Seed | 42 (reproducibility) |
| Primary Model | `microsoft/mdeberta-v3-base` (fine-tuned) |
| Labels | Binary — `0` (Safe) / `1` (Distress) |

---

## 3. Runtime Risk Classification Metrics (9-Stage Pipeline)

When a student submits a vent, the system runs it through **9 sequential stages** to produce a final `risk_level` and `risk_percent`.

### 3.1 Pipeline Stages Overview

| Stage | Name | What It Does |
|-------|------|--------------|
| **Stage 1** | Score Components | Runs text through two AI models (Emotion Classifier + Distress Classifier) to get confidence scores |
| **Stage 2** | Semantic Similarity | Compares vent text against 19 pre-encoded crisis phrases using cosine similarity |
| **Stage 3** | Keyword/Regex Scanning | Scans for keyword matches across 7 categories (suicide, violence, cyberbullying, etc.) |
| **Stage 4** | Sarcasm/Joke Detection | Detects Gen-Z sarcasm markers (e.g., "haha", "charot", 😂, academic context jokes) |
| **Stage 5** | Keyword Match Flags | Aggregates all keyword hits into boolean flags per category |
| **Stage 6** | Psychological Heuristics | Adds score bonuses for cognitive distortions, hopelessness, extreme venting, emotional distress |
| **Stage 7** | Final Rule Engine | Applies sequential priority rules to assign the final risk level |
| **Stage 8** | Contextual Memory (FAISS) | Checks 14-day behavioral trajectory per student for escalation patterns |
| **Stage 9** | Risk Percentage Banding | Converts raw scores to human-readable percentage within the assigned risk band |

---

### 3.2 AI Models Used (4 Total)

| Model # | Model Name | Role | Source |
|---------|------------|------|--------|
| 1 | `j-hartmann/emotion-english-distilroberta-base` | Psychological Emotion Classifier (sadness, fear, anger, joy, etc.) | HuggingFace |
| 2 | `microsoft/mdeberta-v3-base` (fine-tuned) | Distress/Bullying Binary Classifier | Custom Fine-Tuned |
| 3 | `paraphrase-multilingual-MiniLM-L12-v2` | Multilingual Semantic Similarity (crisis phrase matching) | Sentence-Transformers |
| 4 | `facebook/bart-large-mnli` | Zero-Shot Toxicity Disambiguation (self-harm vs. outward violence) | HuggingFace |

---

### 3.3 Base Risk Score Formula

```
risk_score = (0.5 × distress_confidence) + (0.3 × negative_emotion_confidence) + (0.2 × semantic_similarity)
```

| Component | Weight | Source |
|-----------|--------|--------|
| Distress Confidence | **50%** | Model 2 (mDeBERTa fine-tuned classifier) |
| Negative Emotion Confidence | **30%** | Model 1 (Emotion classifier × emotion severity weight) |
| Semantic Similarity | **20%** | Model 3 (Cosine similarity to crisis anchor phrases) |

---

### 3.4 Emotion Severity Weights

The emotion classifier output is multiplied by a severity weight before contributing to the risk score:

| Emotion | Weight | Category |
|---------|--------|----------|
| Sadness | 1.0 | Most severe |
| Grief | 1.0 | Most severe |
| Fear | 0.9 | Severe |
| Anger | 0.8 | High |
| Remorse | 0.8 | High |
| Disappointment | 0.7 | Moderate |
| Nervousness | 0.7 | Moderate |
| Disgust | 0.6 | Moderate |
| Embarrassment | 0.55 | Mild |
| Annoyance | 0.5 | Mild |
| Disapproval | 0.45 | Mild |
| Neutral | 0.0 | No risk |
| Surprise | 0.0 | No risk |
| Joy | 0.0 | No risk |

---

### 3.5 Psychological Heuristic Bonuses

These add to the `risk_score` when detected:

| Heuristic | Score Bonus | Condition |
|-----------|-------------|-----------|
| Cognitive Distortions (e.g., "always", "never", "everyone hates me") | **+0.10** | Always applied when detected |
| Hopelessness (e.g., "pointless", "useless to try") | **+0.15** | Always applied when detected |
| Extreme Venting with profanity | **+0.10** | Only if `risk_score > 0.4` |
| Emotional Distress keywords (e.g., "depressed", "ayoko na") | **+0.10** | Always applied when detected |

---

### 3.6 Keyword Categories (7 Total)

| Category | Label | Examples | Languages |
|----------|-------|----------|-----------|
| **Category 1** | CRITICAL — Suicide Intent | "kill myself", "magpakamatay", "laslas", "tatalon" | English, Tagalog, Ilocano |
| **Category 1b** | CRITICAL — Method + Self-Reference Pairs | "rope" + "myself/hang", "pills" + "overdose" | English, Tagalog |
| **Category 2** | HIGH — Physical Violence/Bullying | "punched", "sinapak", "binugbog", "gikulata" | English, Tagalog, Bisaya |
| **Category 3** | MEDIUM — Verbal Abuse/Cyberbullying | "bobo", "tanga", "ugly", "loser", "doxx" | English, Tagalog, Bisaya |
| **Emotional Distress** | Clinical Depression Indicators | "depressed", "pagod na ako", "di ko na kaya" | English, Tagalog |
| **Finality/Preparation** | Masked Depression Signals | "giving away my", "sorted my stuff", "suko na ko" | English, Tagalog |
| **Sarcasm/Jokes** | Gen-Z False Positive Filters | "lol", "charot", 😂, "bro + exam context" | English, Tagalog |

---

### 3.7 Final Rule Engine (Priority Order)

The rule engine evaluates **top-to-bottom** — the first matching rule determines the risk level:

| Priority | Rule | Risk Level Assigned | Minimum Score |
|----------|------|---------------------|---------------|
| **1** | Semantic Similarity ≥ 0.91 (without Cat1 keywords) | **CRITICAL** | 0.95 |
| **1b** | Semantic Similarity ≥ 0.82 (without Cat1 keywords) | **HIGH** | 0.80 |
| **2** | Cat1 explicit suicide keywords detected | **CRITICAL** | 0.95 |
| **2b** | Cat1 + sarcasm (Masked Depression override) | **CRITICAL** | 0.95 |
| **3** | Finality/Preparation signals | **HIGH** | 0.80 |
| **3b** | Finality + sarcasm (Masked Depression) | **HIGH** | 0.85 |
| **4** | Cat2 physical violence + self-harm disambiguation | **HIGH** | 0.90 |
| **4b** | Cat2 physical violence (standard) | **HIGH** | 0.80 |
| **4c** | Cat2 + sarcasm detected | **MEDIUM** | 0.50 |
| **5** | Emotional distress keywords | **HIGH** | 0.65 |
| **5b** | Emotional distress + sarcasm | **MEDIUM** | 0.40 |
| **6** | Cat3 verbal abuse | **MEDIUM** | 0.60 |
| **6b** | Cat3 + sarcasm | **LOW** | — |
| **7** | Pure score ≥ 0.75 | **HIGH** | — |
| **7b** | Pure score ≥ 0.40 | **MEDIUM** | — |
| **7c** | Heuristic flags only | **MEDIUM** | 0.50 |
| **Default** | No signals detected | **LOW** | — |

---

### 3.8 FAISS Contextual Memory Escalation

The system tracks each student's behavioral trajectory over **14 days** using FAISS:

| Condition | Escalation Action |
|-----------|-------------------|
| **3+ HIGH/CRITICAL entries** in 14 days | Auto-escalate to **CRITICAL** (score ≥ 0.95) |
| **Trajectory slope > 0.05** (risk scores trending upward) | Auto-escalate to **HIGH** (score ≥ 0.75) |
| Trajectory slope < -0.05 | Marked as **IMPROVING** (no escalation) |
| Trajectory stable | Marked as **STABLE** (no escalation) |
| Less than 3 data points | **INSUFFICIENT_DATA** (no action) |

---

### 3.9 Final Risk Percentage Banding

The raw `risk_score` (0.0–1.0) is mapped to a human-readable percentage within the assigned risk band:

| Risk Level | Percentage Range | Description |
|------------|------------------|-------------|
| **LOW** | 1% – 30% | No severe negativity detected |
| **MEDIUM** | 31% – 60% | Moderate negative sentiment or verbal toxicity |
| **HIGH** | 61% – 85% | Physical bullying, emotional distress, or escalation |
| **CRITICAL** | 86% – 100% | Explicit suicidal intent, crisis phrases, or repeated severe distress |

---

## 4. System Output

Every vent analysis returns three values:

| Output Field | Type | Example |
|-------------|------|---------|
| `risk_percent` | Float | `92.0` |
| `risk_level` | String | `"CRITICAL"` |
| `flag_reason` | String | `"CRITICAL: Explicit self-harm or suicidal intent detected. Cognitive distortion detected."` |

---

## 5. Supported Languages

| Language | Keyword Coverage | Semantic Model Support |
|----------|-----------------|----------------------|
| English | ✅ Full | ✅ Full |
| Tagalog (Filipino) | ✅ Full | ✅ Full |
| Bisaya (Cebuano) | ✅ Partial (violence/insult keywords) | ✅ Full |
| Ilocano | ✅ Partial (crisis phrases) | ✅ Full |

---

## 6. Summary Diagram

```
Student Vent Text
    │
    ▼
┌─────────────────────────────────────┐
│  Stage 1: Emotion + Distress AI     │ ──► distress_conf, neg_emo_conf
│  Stage 2: Semantic Similarity       │ ──► semantic_score
│  Stage 3: Keyword Regex Scan        │ ──► category flags
│  Stage 4: Sarcasm Detection         │ ──► is_joke boolean
│  Stage 5: Aggregate Keyword Flags   │
│  Stage 6: Psychological Heuristics  │ ──► score bonuses
│  Stage 7: Rule Engine               │ ──► risk_level
│  Stage 8: FAISS Memory Check        │ ──► escalation override
│  Stage 9: Percentage Banding        │ ──► risk_percent
└─────────────────────────────────────┘
    │
    ▼
{ risk_percent, risk_level, flag_reason }
    │
    ▼
┌──────────────────────────────┐
│  SMS + Email Alert (if HIGH  │
│  or CRITICAL)                │
└──────────────────────────────┘
```

---

> **Note:** This metric system does not replace professional psychological assessment. For clinical screening, the project may integrate a standardized metric such as the PHQ-9 (Patient Health Questionnaire-9) as referenced in the project proposal.

---

*Document generated from codebase analysis of `ml_service/main.py` and `ml_service/train_models.py`.*
*StudentShield v5.0.0 — SVBSM*
