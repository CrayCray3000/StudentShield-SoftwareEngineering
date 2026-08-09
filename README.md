# StudentShield

StudentShield is a Multilingual Machine Learning Framework for Student Sentiment Venting, Bullying Detection, and Automated Crisis Intervention at Saint Vincent Blessed School of Manila (SVBSM).

The system is split into two main components:
1. A CodeIgniter 4 Web Application for the user interface, reporting, and dashboard.
2. A Python ML Microservice for NLP-based sentiment analysis and risk detection.

---

## 📚 Deployment & Operation Guides

All deployment, execution, and management guides are centralized in the `docs/Deployment_Manuals/` folder:

* **[How to Run Guide](docs/Deployment_Manuals/how_to_run.md)**: Local setup for PHP (CodeIgniter 4) and Python (FastAPI).
* **[Azure VM Management & Contingency Guide](docs/Deployment_Manuals/azure_vm_management_and_contingency.md)**: Managing the live Azure VM microservice (`20.187.146.70`) and contingency plans if Azure credit runs out.
* **[Hugging Face Deployment Guide](docs/Deployment_Manuals/huggingface_deployment.md)**: Instructions for deploying to Hugging Face Spaces (Free tier backup).
* **[System Deployment Manual](docs/Deployment_Manuals/system_deployment_manual.md)**: End-to-end production deployment guide.

---

## Quick Start (Local Development)

### Step 1: Database Setup
Import `schema.sql` into MySQL (`student_shield`).

### Step 2: Web App (PHP)
```bash
cd web-app-ci4
composer install
php spark serve
```
Access at [http://localhost:8080](http://localhost:8080)

### Step 3: ML Microservice (Python)
```bash
cd ml_service
pip install -r requirements.txt
python main.py
```
Access docs at [http://localhost:8000/docs](http://localhost:8000/docs)

