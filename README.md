# StudentShield

StudentShield is a Machine Learning Framework for Student Vents, Bullying Detection, and Parent SMS Alerts, specifically designed for Junior High School.

The system is split into two main components:
1. A CodeIgniter 4 Web Application for the user interface, reporting, and dashboard.
2. A Python ML Microservice for NLP-based sentiment analysis and risk detection.

---

## Prerequisites

Ensure you have the following installed:
- PHP 8.1+ (with intl, mbstring, and mysqli extensions enabled)
- Composer (PHP dependency manager)
- MySQL / MariaDB
- Python 3.9+
- Pip (Python package installer)

---

## Step 1: Database Setup

1. Open your MySQL client (e.g., phpMyAdmin, HeidiSQL, or Terminal).
2. Create a new database named `student_shield`.
3. Import the `schema.sql` file located in the root directory:
   ```bash
   mysql -u root -p student_shield < schema.sql
   ```

---

## Step 2: Running the Web Application (PHP)

1. Open a terminal and navigate to the `web-app-ci4` directory:
   ```bash
   cd web-app-ci4
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Configure Environment: Ensure the `.env` file exists and is configured properly with your database settings.
4. Run the development server:
   ```bash
   php spark serve
   ```
5. Access the app: http://localhost:8080

---

## Step 3: Running the ML Service (Python)

1. Open a new terminal and navigate to the `ml_service` directory:
   ```bash
   cd ml_service
   ```
2. Create and activate a virtual environment (Recommended):
   ```bash
   python -m venv .venv
   .venv\Scripts\activate
   ```
3. Install dependencies:
   ```bash
   pip install fastapi uvicorn pydantic transformers torch
   ```
   Note: The first run may take time as it downloads the NLP models (approx. 550MB).
4. Run the service:
   ```bash
   python main.py
   ```
5. Verify it is running by accessing the API documentation at: http://localhost:8000/docs

---

## Testing the Integration

Once both services are running, the PHP app will communicate with the Python service on `localhost:8000` to analyze student vents for sentiment and risk.
You can use the `test_api.php` script in the root directory to test the connection:
```bash
php test_api.php
```
