# 2.1.1 SOFTWARE ARCHITECTURE

This system employs a modern **3-Tier Architecture (Presentation -> Application Logic -> Data)**, which cleanly separates the user interface, the business logic (powered by the MVC pattern), and the data storage mechanisms. This ensures scalability, security, and maintainability for the Student Shield platform.

## 1. Presentation Tier (UI Layer)
The Presentation Tier is the topmost level of the application and is responsible for all user interactions. It ensures that the system is responsive, accessible, and user-friendly across multiple devices.
- **Technologies Used:**
  - **HTML5 & CSS3:** For the structural foundation and styling of the web pages.
  - **Tailwind CSS:** A utility-first framework used to rapidly design custom user interfaces, ensuring a consistent and modern design language.
  - **JavaScript (JS) & AJAX:** Used for asynchronous data fetching to provide a seamless, non-reloading experience (e.g., submitting anonymous vents or updating dashboard metrics in real-time).
  - **Chart.js:** Utilized within the dashboards (Admin/Counselor) to render graphical data and analytics regarding bullying incidents and risk trends.
- **Actors Involved:** Students, Counselors, and Super Admins interact exclusively with this tier.

## 2. Application Logic Tier (Controller / Logic Layer)
The Application Logic Tier is the core of the system where all business rules, routing, security filters, and integrations are executed. It acts as the bridge between the user interface and the database.
- **Technologies Used:**
  - **CodeIgniter 4 (PHP 8+):** The primary MVC framework that handles the backend architecture.
- **Key Components:**
  - **CI4 Routing & Filters:** Intercepts incoming HTTP requests. It applies security measures such as IP Rate Limiting to prevent brute-force attacks and Role-Based Access Control (RBAC) to ensure unauthorized users cannot access restricted pages.
  - **Controllers:** Handle specific domain logic (e.g., `Auth Controller` for logins, `Vent Controller` for handling anonymous messages, and `Bully Controller` for managing incident reports).
  - **AI Risk Engine Sub-system:** Integrates with the **Hugging Face API** to perform NLP (Natural Language Processing) analysis on text submissions. If the API is unreachable, it seamlessly falls back to a **Local Keyword Engine**. A **Risk Scorer** then evaluates the output to determine the severity of the incident.
  - **External Alerts Sub-system:** When critical risks are identified, this module triggers external APIs, utilizing the **Phil SMS Gateway** to send immediate text messages, and **Gmail SMTP** for formal email notifications.

## 3. Data Storage Tier (Data Layer)
The Data Tier is responsible for the secure storage, retrieval, and auditing of all system information. 
- **Technologies Used:**
  - **MySQL Database:** A relational database management system used to store structured data such as user accounts, incident reports, encrypted vents, and system configurations.
  - **JSON Storage:** Used for flat-file system auditing.
- **Key Components:**
  - **AES-256 Encryption:** Highly sensitive data (such as student identities linked to bullying cases, or specific chat logs) are encrypted at rest using AES-256 before being written to the database.
  - **Audit Logs:** Every significant action (e.g., a counselor logging in, a report status being changed, an admin deleting a user) is meticulously recorded into both the MySQL database and redundant JSON logs to ensure a tamper-proof trail for legal and administrative compliance.
