# StudentShield: The Definitive Technical & Operational Knowledge Base

StudentShield is a dual-system mental health and anti-bullying ecosystem engineered for **Saint Vincent Blessed School of Manila (SVBSM)**. This document serves as the primary "Source of Truth" for the platform, detailing every architectural layer, neural pipeline branch, and operational protocol.

---

## 🎯 1. System Philosophy & Institutional Context

### Institutional Rationale
At SVBSM, the transition to Junior High School coincides with a critical developmental window. Traditional mental health reporting often fails because it requires students to physically manifest their distress in a visible way before intervention occurs. StudentShield shifts the intervention paradigm from **Reactive (waiting for a report)** to **Proactive (analyzing existing vents)**.

### The "Golden Hour" of Intervention
The system is built on the psychological principle of the "Golden Hour"—the short window after a crisis entry is made where the student is most receptive to support. By automating the detection of **Critical Risks (Suicide, Self-Harm, Severe Abuse)**, the system sends an immediate "wellness check" SMS to parents, while concurrently alerting counselors for professional follow-up within minutes.

---

## 🏗️ 2. Comprehensive System Architecture

### A. The Hybrid Ecosystem
StudentShield is not a single app; it is a **Distributed Software Ecosystem**:
1.  **The Web Orchestrator (PHP/CI4)**: Manages the student portal, user sessions, and database persistence.
2.  **The Neural Hub (Python/FastAPI)**: A dedicated high-performance server that handles all heavy Natural Language Processing (NLP).
3.  **The Security Layer**: Implements **Sodium-based AES-256-GCM encryption** for all incoming student text.
4.  **The Alert Gateway**: Integrated with **Semaphore (SMS)** and **SMTP (Email)** for real-time Dual-Dispatch Automated Alerts (Parents and Counselors).

### B. Core Technology Stack
*   **Web Backend**: PHP 8.1+ / CodeIgniter 4.4
*   **AI Microservice**: Python 3.10+ / FastAPI 0.109
*   **Vector Engine**: FAISS (Facebook AI Similarity Search)
*   **Database**: MySQL 8.0 (InnoDB Engine)
*   **Frontend**: Tailwind CSS 3.0 / Vanilla JavaScript
*   **AI Frameworks**: PyTorch / Transformers / Sentence-Transformers

---

## 🧠 3. Neural Analysis Methodology (Deep Dive)

Every student submission triggers a **Parallel Inference Pipeline** inside the ML Microservice.

### A. The 9-Stage Analysis Loop
1.  **Emotion Vectoring**: Uses `distilroberta-base` to extract 7 core emotions (Sadness, Joy, Fear, Anger, Surprise, Disgust, Neutral).
2.  **Fine-Tuned Distress Detection**: A secondary classifier (mDeBERTa-v3) scans for specific linguistic markers of mental health crisis.
3.  **Multilingual Semantic Search**: The text is converted into a 384-dimensional vector and compared against a **Crisis Anchor Set** containing phrases in:
    *   **English**: *"I want to give up"*
    *   **Tagalog**: *"Ayoko na talaga, pagod na ko"*
    *   **Ilocano**: *"Kayat ko laengen a mabatay"*
    *   **Bisaya**: *"Gusto nako mamatay"*
4.  **Taglish & Gen-Z Slang Bias-Corrector**: The AI explicitly processes "Taglish" (Tagalog-English code-switching) and extreme Gen-Z abbreviations (e.g., "slay," "awit," "gg") directly within its NLP layers, preventing false positives tied to local dialect variations.
5.  **Continuous Adaptive Filtering**: Overcomes future slang decay by allowing counselors to flag false positives. This feedback loops into an AI retraining pipeline to dynamically adapt to the evolving student lexicon.
6.  **Contextual Memory (FAISS) Integration**:
    *   The system retrieves the last 14 days of the student's history.
    *   It calculates the "Trend Velocity"—if a student is becoming increasingly negative across multiple entries, the risk is automatically escalated.

### B. Risk Categorization Tiers
| Tier | Score Range | Description | Action |
| :--- | :--- | :--- | :--- |
| **LOW** | 1% - 30% | Neutral or positive venting. | Logged only. |
| **MEDIUM** | 31% - 60% | Moderate distress or verbal insults. | Flagged for counselor review. |
| **HIGH** | 61% - 85% | Physical violence reports or severe distress. | Urgent priority in dashboard. |
| **CRITICAL** | 86% - 100% | Direct suicidal intent or imminent self-harm. | **Automated Contextual SMS to Parents + Counselor Alert.** |

---

## 🔐 4. Data Privacy, Encryption & Ethics

### Security Protocols
*   **Application-Level Encryption at Rest**: Student "Vents" are symmetrically encrypted before storage. The `raw_text` in the database is unreadable natively without the specific application-level decryption key, resolving conflicts with regex fallbacks.
*   **API Security**: The connection between the Web App and the ML Microservice is secured via a **Production-Grade API Key** and header validation.
*   **Audit Logging**: Every action taken by a counselor—viewing, resolving, or deleting a vent—is logged with an IP address and User Agent to prevent unauthorized data access.

### Ethics of Intervention
The system utilizes an **Escalated Anonymity Model**. Students remain anonymous during "Low/Medium" sessions to encourage open venting. However, the system's "Duty of Care" logic ensures that upon a life-threatening **Critical Risk**, anonymity is lifted for authorized counselors, and an automated SMS is sent to parents. Crucially, the parent SMS is phrased as a "high-stress wellness check" rather than a clinical diagnosis, protecting the student in cases of domestic abuse while fulfilling the system's automated mandate.

---

## 👥 5. User Journeys & Workflows

### The Student Journey
1.  **Login**: Secure access via Student ID and Passcode.
2.  **Venting & Silent Panic**: The student enters text into the **"Student Shield" Portal**. Alternatively, if they are trapped in an active physical bullying scenario and cannot type, a discrete double-tap on the **"Silent Panic"** UI instantly triggers a Code Red Parent/Counselor Alert.
3.  **Real-Time Protection**: Within milliseconds, the AI analyzes the text.
4.  **Post-Vent Grounding UI**: Upon submission of a highly distressed vent, the platform seamlessly transitions into a 60-second interactive breathing exercise (CSS/JS animation) to proactively calm the student during their psychological "Golden Hour".

### The Counselor Journey & Parent Resolution Loop
1.  **Dashboard Overview**: Counselors view a "Snapshot" of school-wide emotional health.
2.  **Incident Management**: High-risk "Vents" are listed at the top.
3.  **AI-Assisted Triage**: The system summarizes critical points of long vents, saving reading time and easing counselor bottlenecks.
4.  **Resolution Tracking**: Counselors can mark cases as "Under Investigation" or "Resolved" after a face-to-face meeting.
5.  **Automated 24-Hour Resolution Link**: 24 hours after an automated Parent/Counselor SMS is sent, the system automatically sends a follow-up link (e.g., "Has the incident been settled at home?") to close the data loop and enforce accountability.
6.  **Bullying Hotspot Analysis**: The ML extracts location entities to build a visual "Heatmap" on the dashboard, allowing the Principal to strategically deploy guards/prefects to specific school zones.
7.  **Corroboration Logic**: Automatically halts any automated discipline from bullying reports; AI flags serve strictly as alerts that mandate manual human verification to prevent malicious weaponization by peers.

---

## 🛠️ 6. System Deployment & Fail-Safes

### Local Regex Fallback (The Passive Layer)
StudentShield includes a "Safety Net for the Safety Net." If the AI Microservice is unreachable (e.g., server downtime), the PHP backend automatically activates a **Local Keyword Fallback**. This regex engine operates *before* the application encrypts the payload for the database, ensuring that explicit crisis words (like *"suicide"*, *"kill"*, *"bigti"*) still trigger alerts securely even without the deep-learning layer.

### Deployment Environment
*   **Operating System**: Windows (Laragon/WAMP) or Linux (Ubuntu/Nginx).
*   **Production Hardware**: Recommended Cloud GPU instance (AWS EC2 / GCP) or local server with min. 16GB RAM for concurrent inference, avoiding the severe memory exhaustion limits of 4GB local setups.

---

## 📖 7. Glossary of Terms

*   **Transformer**: The "Neural Model" type (like BERT) that understands the relationship between words in a sentence.
*   **Vector Embedding**: A mathematical representation of text that allows the AI to "measure" how similar two different sentences are.
*   **FAISS**: Facebook's library for searching through thousands of emotional patterns in microseconds.
*   **Sentiment Polarity**: Measuring if a text is positive, negative, or neutral.
*   **Zero-Shot Classification**: An AI ability to classify text into topics it wasn't explicitly trained with by using logical "reasoning."

---

> [!IMPORTANT]
> **NotebookLM User Note**: This document is comprehensive. If you are asking about the "Logic," refer to Section 3. If you are asking about "Safety," refer to Section 4. For "Technical Setup," refer to Section 2 and 6.
