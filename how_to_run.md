# StudentShield: How to Run Guide

This guide provides step-by-step instructions to set up and run both the **CodeIgniter 4 Web Application** and the **Python ML Microservice**.

---

## 📋 Prerequisites

Ensure you have the following installed:
- **PHP 8.1+** (with `intl`, `mbstring`, `mysqli` extensions enabled)
- **Composer** (PHP dependency manager)
- **MySQL / MariaDB**
- **Python 3.9+**
- **Pip** (Python package installer)

---

## 🛠️ Step 1: Database Setup

1. Open your MySQL client (e.g., phpMyAdmin, HeidiSQL, or Terminal).
2. Create a new database named `student_shield`.
3. Import the `schema.sql` file located in the root directory:
   ```bash
   mysql -u root -p student_shield < schema.sql
   ```
   *(Alternatively, copy-paste the contents of `schema.sql` into your SQL query window.)*

---

## 🌐 Step 2: Running the Web Application (PHP)

1. Open a terminal and navigate to the `web-app-ci4` directory:
   ```bash
   cd web-app-ci4
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Configure Environment:
   - Ensure the `.env` file exists (I have verified it is already configured with `database.default` settings).
4. Run the development server:
   ```bash
   php spark serve
   ```
5. Access the app: [http://localhost:8080](http://localhost:8080)

---

## 🧠 Step 3: Running the ML Service (Python)

1. Open a **new** terminal and navigate to the `ml_service` directory:
   ```bash
   cd ml_service
   ```
2. (Optional but Recommended) Create a virtual environment:
   ```bash
   python -m venv .venv
   .venv\Scripts\activate
   ```
3. Install dependencies:
   ```bash
   pip install fastapi uvicorn pydantic transformers torch
   ```
   *(Note: The first run may take time as it downloads the ~550MB mDeBERTa-v3 model.)*
4. Run the service:
   ```bash
   python main.py
   ```
5. Verify it's running: [http://localhost:8000/docs](http://localhost:8000/docs)

---

## 🧪 Testing the Integration

- Once both services are running, the PHP app will communicate with the Python service on `localhost:8000` to analyze student vents for sentiment and risk.
- You can use the `test_api.php` script in the root to test the connection:
  ```bash
  php test_api.php
  ```
