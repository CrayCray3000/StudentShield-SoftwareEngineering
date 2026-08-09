# StudentShield: How to Run Guide

This guide provides step-by-step instructions to set up, configure, and run both the **CodeIgniter 4 Web Application** and the **Python ML Microservice**.

---

## 📋 Prerequisites

Ensure you have the following installed on your system:
- **PHP 8.1+** (with `intl`, `mbstring`, `mysqli`, `openssl`, `curl` extensions enabled)
- **Composer** (PHP dependency manager)
- **MySQL / MariaDB** (via Laragon, XAMPP, or standalone MySQL service)
- **Python 3.9+** (with `pip` package installer)

---

## 🛠️ Step 1: Database Setup

1. Open your MySQL management tool (phpMyAdmin, HeidiSQL, MySQL Workbench, or CLI).
2. Create a new database named `student_shield`:
   ```sql
   CREATE DATABASE student_shield CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Import the `schema.sql` file located in the project root:
   ```bash
   mysql -u root -p student_shield < schema.sql
   ```
   *(Or execute the queries from `schema.sql` inside your database manager GUI).*

---

## 🌐 Step 2: Running the Web Application (PHP - CodeIgniter 4)

1. Open a terminal and navigate to the web application directory:
   ```bash
   cd web-app-ci4
   ```
2. Install PHP dependencies via Composer:
   ```bash
   composer install
   ```
3. Configure the `.env` file:
   - Ensure a `.env` file exists in `web-app-ci4/` (copy from `env` if needed).
   - Set database credentials, app URL, and SMS/Email API keys:
     ```ini
     CI_ENVIRONMENT = development
     app.baseURL = 'http://localhost:8080/'

     database.default.hostname = localhost
     database.default.database = student_shield
     database.default.username = root
     database.default.password = 
     database.default.DBDriver = MySQLi
     ```
4. Run CodeIgniter migrations (optional/recommended):
   ```bash
   php spark migrate
   ```
5. Start the local development server:
   ```bash
   php spark serve
   ```
6. Access the application in your browser:
   - **Student Portal & Register**: [http://localhost:8080/login](http://localhost:8080/login)
   - **Counselor / Admin Portal**: [http://localhost:8080/admin/login](http://localhost:8080/admin/login)

---

## 🧠 Step 3: Running the ML Microservice (Python FastAPI)

1. Open a **new** terminal window and navigate to the `ml_service` directory:
   ```bash
   cd ml_service
   ```
2. Create and activate a Python virtual environment:
   - **Windows (PowerShell/CMD)**:
     ```bash
     python -m venv .venv
     .venv\Scripts\activate
     ```
   - **macOS / Linux**:
     ```bash
     python3 -m venv .venv
     source .venv/bin/activate
     ```
3. Install required Python packages:
   ```bash
   pip install -r requirements.txt
   ```
   *(Note: The initial run downloads NLP assets such as mDeBERTa-v3 model files).*

4. Launch the FastAPI server:
   ```bash
   python main.py
   ```
   *(Or using uvicorn directly: `uvicorn main:app --host 0.0.0.0 --port 8000 --reload`)*

5. Verify ML API service health and documentation:
   - **Swagger OpenAPI Docs**: [http://localhost:8000/docs](http://localhost:8000/docs)
   - **Health Check**: [http://localhost:8000/health](http://localhost:8000/health)

---

## 🧪 Testing the Integration

- Once both `php spark serve` (Port `8080`) and `python main.py` (Port `8000`) are running, StudentShield will automatically analyze submitted student vents for sentiment, emotion, and risk categorization via REST API calls.
- Twilio SMS / Email alerts will automatically trigger for critical vent classifications when configured in `.env`.
