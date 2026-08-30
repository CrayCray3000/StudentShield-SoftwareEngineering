# 🤖 Instructions for Autonomous Coding Agents (Google Jules, Copilot, etc.)

> **Repository:** StudentShield (SVBSM Mental Health & Anti-Bullying Platform)  
> **Target Branch:** `main`

---

## 🏗️ 1. System Architecture & Constraints

StudentShield is a **distributed 2-cloud application**:
1. **Web Frontend & Backend (`web-app-ci4/`)**:
   - **Framework:** PHP 8.1+ / CodeIgniter 4
   - **Domain & SSL:** `https://studentshield.app` (Protected by Cloudflare Universal SSL)
   - **Production Hosting:** InfinityFree (Shared PHP Hosting)
   - **Deployment:** GitHub Actions (`.github/workflows/ftp-deploy.yml`) auto-syncs `web-app-ci4/` to InfinityFree on push to `main`.
   - **Database:** MySQL / MariaDB (Application-level AES-256-CTR encryption for vent logs)
   - **Constraint:** Production has **NO CLI / SSH access**. Never introduce dependencies or workflows that require runtime CLI commands on the PHP production server.

2. **Machine Learning Microservice (`ml_service/`)**:
   - **Framework:** Python 3.10+ / FastAPI / PyTorch
   - **Production Hosting:** Hugging Face Spaces (`ashlogg/student-shield-official`, Docker SDK)
   - **Models:**
     - `SamLowe/roberta-base-go_emotions` (28-class public emotion model)
     - `ashlogg/student-shield-mdeberta` (Fine-tuned crisis detection on Hugging Face Hub)
   - **Deployment:** GitHub Actions (`.github/workflows/deploy_hf.yml`) auto-syncs `ml_service/` to Hugging Face Spaces on push to `main`.

---

## 🛡️ 2. Critical Guardrails (DO NOT BREAK)

When working on bug fixes, performance, or refactoring:

### A. Authentication & Security
- **API Key Security:** The ML endpoint `/analyze-text` requires `X-API-KEY: svbsm-production-ml-key-v1`. Never remove authentication headers or expose secrets.
- **Data Privacy at Rest:** Student vent text (`raw_text`) in `web-app-ci4` is symmetrically encrypted using CodeIgniter 4's `Services::encrypter()` (`AES-256-CTR`). Do not bypass or alter the encryption format without backwards compatibility.
- **Admin & Counselor 2FA:** Restored 6-digit email OTP for counselor and admin logins with automatic fallback.

### B. Fallback & Clinical Alert Triage
- **Dual-Layer Risk Evaluation:** `VentController.php` runs a local keyword regex fallback before database persistence. If the ML service is down, the PHP fallback is the life safety net.
- **Clinical Triage Guardrails:** 
  - `CRITICAL` (86%–100%) triggers immediate Counselor and Parent notifications.
  - `HIGH` (61%–85%) triggers Counselor triage only (to prevent parent alarm fatigue and preserve student trust).
  - Passive disappearance ideation (C-SSRS Level 2) is mapped to `HIGH (79%)`.
- **Preserve Taglish / Slang Lexicon:** Do not remove or simplify Tagalog, Taglish, Bisaya, or Ilocano crisis phrases in `VentController.php` or `main.py`.

### C. Performance & Queries
- When fixing **N+1 queries** in `AdminController.php` or `VentController.php`, use batch `whereIn()` queries or single-pass joins. Ensure audit logging (`logAudit()`) does not trigger individual DB writes inside large loops.

### D. Testing & Code Quality
- Add PHPUnit tests in `web-app-ci4/tests/` and PyTest tests in `ml_service/tests/`.
- Ensure all tests pass without requiring an active internet connection or live database connection (use mocks where appropriate).

---

## 📁 3. Directory Layout
```
├── web-app-ci4/          # CodeIgniter 4 PHP Web Application
│   ├── app/Controllers/  # VentController, AuthController, AdminController, BullyReportController
│   ├── app/Models/       # User & incident database models
│   ├── app/Views/        # HTML / Tailwind CSS dashboard views
│   └── tests/            # PHPUnit test suite
├── ml_service/           # FastAPI Machine Learning Service
│   ├── main.py           # FastAPI server & inference pipeline
│   ├── train_models.py   # PyTorch / Transformers fine-tuning script
│   └── data/             # Training datasets (merged_train.csv, val_vents.csv)
├── AI_CONTEXT.md         # Full system documentation for AI assistants
└── schema.sql            # Full MySQL database schema
```
