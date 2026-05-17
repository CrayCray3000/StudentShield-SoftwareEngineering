# StudentShield: Academic Architecture Reference

This document is designed to be copy-pasted into your **Chapter 2 (Review of Related Literature)** and **Chapter 3 (Methodology)** when you are ready to write them. It uses academic, formal language to describe exactly how the ML system works and defends the choice to use advanced models.

---

## 1. Justification for Advanced Deep Learning (For Chapter 1 or 3)

**Why "Basic Sentiment Classification" wasn't enough:**
> "While early iterations of the proposed framework considered basic sentiment classification (e.g., VADER or Naive Bayes), preliminary testing revealed severe limitations in handling Filipino-English code-switching (Taglish), adolescent slang, and sarcastic expressions. Basic sentiment analyzers operate on rigid keyword polarities, often misclassifying academic frustrations (e.g., 'this exam is killing me') as critical risks, while missing passive suicidal ideation masked by humor (e.g., 'para saan pa ako haha'). 
> 
> To ensure the safety and reliability of the StudentShield platform, the architecture was upgraded to utilize a Multi-Model Deep Learning Ensemble. The system leverages lightweight, pre-trained Transformer models optimized for CPU inference. This approach provides the contextual understanding of Large Language Models (LLMs) without the prohibitive computational costs and privacy risks associated with cloud-based generative AI."

## 2. The Multi-Model Ensemble Architecture (For Chapter 3 / Methodology)

**How the ML pipeline works:**
> "The StudentShield risk assessment engine operates on a multi-stage, ensemble machine learning pipeline designed to achieve high precision in crisis detection while minimizing false positives. The architecture consists of the following components:
> 
> **1. Primary Distress Classification (mDeBERTa & Fallbacks):** 
> The core distress analysis is powered by a fine-tuned multilingual DeBERTa model (`ashlogg/student-shield-mdeberta`), which is highly sensitive to adolescent psychological distress patterns. To guarantee high availability, the system implements a fail-safe mechanism utilizing `SamLowe/roberta-base-go_emotions` and `unitary/toxic-bert` as fallback models in the event of primary model failure.
> 
> **2. Granular Emotion Extraction (DistilRoBERTa):** 
> A secondary classification layer utilizes `j-hartmann/emotion-english-distilroberta-base` to extract six core emotions (sadness, fear, anger, disgust, surprise, and joy). This allows the system to differentiate between active anger (potential bullying) and profound sadness (potential depression), weighting the final risk score accordingly.
> 
> **3. Semantic Crisis Matching (MiniLM):** 
> To detect implicit or creatively phrased distress signals, the framework employs `paraphrase-multilingual-MiniLM-L12-v2`. This model converts the student's text into high-dimensional vector embeddings and calculates the cosine similarity against a database of known, clinically significant crisis anchors (e.g., explicit self-harm intent).
> 
> **4. Contextual Behavioral Tracking (FAISS):** 
> Recognizing that psychological distress is often cumulative, the system utilizes Facebook AI Similarity Search (FAISS) to maintain a secure, localized vector memory of each student's historical entries. By calculating the slope of risk escalation over a 14-day rolling window, the algorithm can dynamically boost the risk score if a student exhibits an escalating trajectory of despair, enabling early intervention before a critical incident occurs."

## 3. Two-Tiered Safety Net (For Chapter 3 / System Design)

**How the PHP web app and Python ML service work together:**
> "To guarantee platform resilience, StudentShield implements a Two-Tiered Assessment Protocol. The primary tier is the Python-based AI Microservice, which conducts deep semantic analysis. However, network latency or microservice downtime poses a significant risk in crisis management systems. 
> 
> To mitigate this, the secondary tier exists within the PHP Application Layer as a Local Heuristic Fallback. Regardless of the AI microservice's output, the PHP controller independently scans the entry against a categorized lexicon of extreme distress and physical violence markers. The system compares the AI-generated risk score against the heuristic risk score and consistently applies the *higher* of the two values. This "safety-first" override ensures that critical incidents (e.g., explicit mentions of self-harm or physical abuse) trigger immediate counselor notifications even during total machine learning service outages."

## 4. Privacy and Encryption (For Chapter 3 / Security)

**How data is protected:**
> "Given the highly sensitive nature of student mental health disclosures, StudentShield enforces strict data-at-rest encryption. All submitted vents and incident reports undergo Advanced Encryption Standard (AES-256) encryption prior to database insertion. The raw text is converted to a hexadecimal string, rendering it entirely unreadable to database administrators or malicious actors in the event of a data breach. Decryption occurs exclusively within the volatile memory of the authorized counselor's dashboard session, strictly adhering to the principles of the Data Privacy Act of 2012 (Republic Act No. 10173)."
