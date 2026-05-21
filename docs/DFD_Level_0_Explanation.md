# Figure 3. DFD Level 0 (Context Diagram) Explanation

The Context Diagram defines the operational boundary of the StudentShield system, positioning it as the central processor that manages data exchanges between students, guidance personnel, and external services. The system is designed to facilitate anonymous emotional expression, structured bullying reporting, real-time crisis identification, and rapid administrative response.

## External Entities and Data Flows

### 1. Student
The Student entity represents the primary user of the web application. 
- **Input Data Flows (Student to System):**
  - **Login Credentials:** The student provides authentication details to access personal portals and submit content.
  - **Venting Entry:** The student submits anonymous text-based thoughts, emotional vents, and mood self-assessments.
  - **Bullying Report:** The student uploads descriptions, locations, dates, and multimedia evidence regarding bullying incidents.
- **Output Data Flows (System to Student):**
  - **Session Token:** The system issues authentication tokens upon successful login to authorize user sessions.
  - **Status Confirmation:** The system sends receipt confirmations for submitted vents and reports, along with status updates.

### 2. Counselor / Super Admin
This entity represents the school’s guidance counselors and system administrators who monitor student safety.
- **Input Data Flows (Counselor to System):**
  - **Case Verification:** Counselors submit updates, resolution logs, and intervention statuses after reviewing cases.
  - **System Configuration:** Administrators input parameter adjustments, threshold settings, and user access definitions.
- **Output Data Flows (System to Counselor):**
  - **Real-time Alert:** The system immediately triggers urgent notifications when critical distress levels are detected.
  - **Dashboard Data:** The system populates the administrative interface with aggregated case details, metrics, and logs.
  - **Venting Analysis Results:** The system delivers sentiment analysis, emotion distribution, and risk classifications of students' venting activities.

### 3. HuggingFace ML Microservice
This external service provides natural language processing (NLP) to classify text for emotional and safety risks.
- **Output Data Flow (System to ML Microservice):**
  - **Raw Vent Text:** The system sends raw, unencrypted student vents to the microservice for text evaluation.
- **Input Data Flows (ML Microservice to System):**
  - **Risk Score:** The microservice returns a percentage-based safety risk metric.
  - **Emotion Classification:** The microservice provides mapped primary emotional states (e.g., sadness, anger).

### 4. Parent / Guardian
This entity represents the student's legal guardian who receives emergency notifications in critical situations.
- **Output Data Flows (System to Parent):**
  - **SMS Alert:** The system sends automated text notifications when a student is classified under a high or critical risk tier.
  - **Email Alert:** The system dispatches HTML-formatted email alerts to provide detailed case summaries and contact instructions.
