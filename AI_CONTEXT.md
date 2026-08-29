# StudentShield — AI Context & Infrastructure Guide

> **Purpose:** This document is written for AI assistants picking up this project mid-development. Read this before touching any code or configs. It covers where everything is deployed, how it's connected, and how to manage it.

---

## 🏗️ Project Overview

**StudentShield** is a web-based mental health monitoring system for students at SVBSM (Saint Vincent Blessed School of Manila). It allows students to anonymously "vent" feelings, which are analyzed for psychological risk (LOW / MEDIUM / HIGH / CRITICAL) using a Python ML service. Counselors and admins receive alerts for high-risk submissions.

**Stack:**
- **Backend:** CodeIgniter 4 (PHP) — served via InfinityFree hosting
- **ML Service:** Python FastAPI — deployed on Hugging Face Spaces (Docker)
- **Local Dev:** Laragon on Windows (`D:\laragon\www\student shield\`)
- **Database:** MySQL (local: Laragon root/no-password; production: InfinityFree MySQL)
- **SMS Alerts:** Local Android SMS Gateway app (LAN IP: `192.168.1.3:8080`)
- **Email Alerts:** Gmail SMTP (`vonnchristianmielmeneses@gmail.com`)
- **Repo:** `https://github.com/mieell/student-shield-official` (branch: `main`)

---

## 📁 Repository Structure

```
student-shield/
├── web-app-ci4/          # CodeIgniter 4 PHP web application
│   ├── app/
│   │   ├── Controllers/  # VentController.php (core logic), AuthController, etc.
│   │   ├── Views/        # vent_form.php, dashboards, login, etc.
│   │   ├── Models/       # User models
│   │   └── Config/       # Routes.php, Security.php, etc.
│   ├── public/           # Web root (point server here)
│   └── .env              # Environment config (NOT committed — see below)
├── ml_service/           # Python FastAPI ML service
│   ├── main.py           # FastAPI app (risk analysis, FAISS memory)
│   ├── Dockerfile        # HF Spaces Docker config (port 7860)
│   ├── requirements.txt  # Python deps
│   ├── models/           # Trained sklearn/joblib models
│   ├── faiss_memory/     # FAISS vector store for student history
│   └── data/             # Training data
├── .github/workflows/
│   └── deploy_hf.yml     # Auto-deploys ml_service/ to Hugging Face on push
├── schema.sql            # Full MySQL schema (run this to recreate DB)
├── AI_CONTEXT.md         # THIS FILE
├── MANAGEMENT_GUIDE.md   # Additional management notes
└── docs/
    └── Deployment_Manuals/
        └── azure_vm_management_and_contingency.md  # Legacy Azure doc (Azure is NO LONGER used)
```

---

## ☁️ Deployment Map

### 1. Web App — InfinityFree (Free PHP Hosting) + Name.com Domain
 
| Item | Value |
|---|---|
| **Host** | InfinityFree (Account: `if0_41428179`) |
| **Custom Domain** | `https://studentshield.app` (Name.com registrar, expires Aug 29, 2027) |
| **Nameservers** | `ns1.infinityfree.com`, `ns2.infinityfree.com` |
| **SSL / HTTPS** | Let's Encrypt Free SSL (Enforced via `.app` HSTS) |
| **Legacy Subdomain** | `https://studentshield.gt.tc/` (Alias) |
| **Web root** | Points to `web-app-ci4/public/` |
| **PHP version** | 8.1+ (CI4 requirement) |
| **Deployment method** | **Manual FTP/file upload** (no CI/CD) |
| **Database** | InfinityFree MySQL (managed via phpMyAdmin in their control panel) |
| **DB name** | `if0_41428179_studentshield` (or `student_shield` locally) |

> ⚠️ **InfinityFree limitations**: No SSH, no CLI, no `php spark` commands. All DB changes must be done via phpMyAdmin. File changes via FTP or their file manager.

### 2. ML Service & Model Repositories — Hugging Face

| Item | Value |
|---|---|
| **FastAPI Space (Runtime)** | `https://huggingface.co/spaces/ashlogg/student-shield-official` |
| **Direct API Endpoint** | `https://ashlogg-student-shield-official-2285456.hf.space` |
| **Fine-Tuned Model Repo** | `https://huggingface.co/ashlogg/student-shield-mdeberta` |
| **Emotion Model (Public)** | `SamLowe/roberta-base-go_emotions` (28 emotions) |
| **Trained Architecture** | `microsoft/mdeberta-v3-base` (Fine-tuned for Taglish/Filipino crisis detection) |
| **Training Dataset** | 3,251 Train / 814 Val samples (Balanced with 65 real decrypted vents + synthetic Taglish/Bisaya/Ilocano data) |
| **HF Username** | `ashlogg` |
| **Internal Port** | `7860` (standard HF Docker port) |
| **Auto-deploy** | ✅ Yes — GitHub Actions (`deploy_hf.yml`) triggers on push to `main` when `ml_service/**` changes |

**How the ML Pipeline Works (v7.5):**
```
1. Student submits vent → PHP VentController sends HTTP POST to HF Space API
2. Space loads fine-tuned mDeBERTa from ashlogg/student-shield-mdeberta (or local cache)
3. Emotion classification via SamLowe/roberta-base-go_emotions
4. 79 multilingual crisis anchor embeddings computed & compared via mDeBERTa
5. Smiling/Masked Depression detection overrides false positive laughter dampening
6. Rule-based safety overrides (Cat 1/2/3, Self-Worth, Anhedonia) & FAISS trajectory memory evaluated
7. Returns risk_score (0-100%) and risk_level (LOW/MEDIUM/HIGH/CRITICAL)
```

**Autonomous Coding Agents (Google Jules & Copilot):**
- System constraints & guardrails: [`AGENTS.md`](./AGENTS.md) and [`.github/copilot-instructions.md`](./.github/copilot-instructions.md)
- Dataset extraction CLI: `php spark decrypt:vents` (decrypts real vents for ML fine-tuning)

**To deploy ML changes manually:**
```bash
git subtree push --prefix ml_service https://ashlogg:<HF_TOKEN>@huggingface.co/spaces/ashlogg/student-shield-official main
```

### 3. LOCAL DEVELOPMENT — Laragon (Windows)

| Item | Value |
|---|---|
| **Path** | `D:\laragon\www\student shield\` |
| **MySQL binary** | `D:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe` |
| **Local URL** | `http://student-shield.test` (or whatever Laragon assigns) |
| **DB user/pass** | `root` / *(no password)* |
| **DB name** | `student_shield` |
| **PHP** | Via Laragon's bundled PHP |

---

## 🔑 Environment Variables (`.env`)

The `.env` file lives at `web-app-ci4/.env` and is **NOT committed to git** (gitignored). This is the current config:

```ini
CI_ENVIRONMENT = development          # Change to "production" on server

app.baseURL = 'https://studentshield.app/'
app.appTimezone = 'Asia/Manila'

# DATABASE (local Laragon values — override on InfinityFree server)
database.default.hostname = localhost
database.default.database = student_shield
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306

# ENCRYPTION KEY (required for vent text encryption)
encryption.key = hex2bin('785a21faeb289ff35b887bd75168ceb78c950a7f1a3b8fc7d92a106e23b7e4d8')

# ML SERVICE (Hugging Face)
ML_API_URL = https://ashlogg-student-shield-official-2285456.hf.space
ML_API_KEY = studentshield_secret_key_2026

# EMAIL ALERTS (Gmail SMTP)
email.protocol = smtp
email.SMTPHost = smtp.gmail.com
email.SMTPUser = vonnchristianmielmeneses@gmail.com
email.SMTPPass = "vsmanuqipbmfasht"
email.SMTPPort = 587
email.SMTPCrypto = tls

# COUNSELOR CONTACTS
COUNSELOR_PHONE = "09221234567"
COUNSELOR_EMAIL = "svbsmcounseling@gmail.com"

# LOCAL SMS GATEWAY (Android phone on LAN — only works locally)
sms.GatewayUrl = "http://192.168.1.3:8080/send-sms"

# CLOUD SMS (CloudSMS API)
CLOUDSMS_APP_KEY="F5AD8A6AC7614144828FD80D6B5A454B"
CLOUDSMS_APP_SECRET="WAiTLHJ2GvVgRGEnehcpmaIMMAkTQb7uZtb143js"
```

> ⚠️ **On production (InfinityFree)**, the server has its own `.env` with the InfinityFree DB hostname, username, and password. You cannot SSH in to see it — it was set manually via their file manager.

---

## 🗄️ Database Schema

Run `schema.sql` from the repo root to recreate the database. Key tables:

| Table | Purpose |
|---|---|
| `users` | All users (students, counselors, admins). `role`: 0=Student, 1=Counselor, 2=Admin |
| `students` | Student profile linked to `users.id` — stores parent phone/email, section, grade |
| `counselors` | Counselor profile |
| `admins` | Admin profile |
| `vent_logs` | Core table — stores encrypted vent text + ML analysis results |
| `bully_reports` | Bullying incident reports |
| `counselor_notes` | Notes written by counselors about students |
| `case_status_history` | Audit trail of case status changes |
| `sms_logs` | Log of sent SMS alerts |
| `email_logs` | Log of sent email alerts |
| `virtual_meetings` | Video session records (Daily.co links) |
| `audit_logs` | System-wide action audit trail |

### `vent_logs` — Full Column List (Production server must have ALL of these)

```sql
id, user_id, raw_text (encrypted), emotions, risk_percent,
risk_level, trajectory, escalation_probability,
status (Pending/Reviewed/Flagged), created_at, deleted_at
```

> ⚠️ **Known issue**: The `schema.sql` file is outdated and missing `trajectory` and `escalation_probability`. If setting up a fresh DB, add them:
> ```sql
> ALTER TABLE vent_logs
>   ADD COLUMN trajectory VARCHAR(50) DEFAULT 'INSUFFICIENT_DATA' AFTER risk_level,
>   ADD COLUMN escalation_probability DECIMAL(5,4) DEFAULT 0.0 AFTER trajectory;
> ```

---

## 🤖 ML Service Architecture

**File:** `ml_service/main.py` (FastAPI, ~72KB — large file)

- Exposes `/analyze-text` (POST) — main endpoint used by `VentController.php`
- Accepts: `{ "raw_text": "...", "student_id": "..." }`
- Returns: `{ "risk_level": "LOW|MEDIUM|HIGH|CRITICAL", "risk_score": 0.0-1.0, "trajectory": "...", "escalation_probability": 0.0 }`
- Uses: trained sklearn models + FAISS vector memory for per-student trajectory tracking
- API key auth: `X-API-KEY` header = `studentshield_secret_key_2026`

**Fallback:** If the HF Space is cold-starting or unreachable, `VentController.php` automatically falls back to `localKeywordFallback()` — a PHP keyword heuristic that still catches high-risk Taglish phrases. No user-facing error is shown.

---

## 🔄 Key Workflows

### Submitting a Vent (Student Flow)
1. Student fills `vent_form.php` → AJAX POST to `/student/vent/submit`
2. `VentController::submit()` validates (auth, rate-limit 30s, length <5000 chars)
3. Calls HF ML service (`/analyze-text`) via cURL (10s connect timeout, 60s total)
4. Runs local PHP keyword fallback, takes the **higher** risk level of the two
5. Encrypts vent text with `hex2bin` key → stores to `vent_logs`
6. If CRITICAL/HIGH → sends SMS (local gateway or CloudSMS) + email alert
7. Returns JSON → JS shows success toast or empathy modal

### Deploying Web App Changes
Since InfinityFree has no CI/CD:
1. Edit files locally in `D:\laragon\www\student shield\web-app-ci4\`
2. Commit + push to GitHub (`git push origin main`)
3. Manually upload changed files via InfinityFree FTP or file manager
4. **Never** push `.env` or `vendor/` to GitHub (gitignored)

### Deploying ML Service Changes
1. Edit files in `ml_service/`
2. Commit + push to GitHub (`git push origin main`)
3. GitHub Actions (`deploy_hf.yml`) automatically pushes `ml_service/` to HF Spaces
4. HF rebuilds Docker container (takes ~2-5 min)
5. Monitor at: `https://huggingface.co/spaces/ashlogg/student-shield-official`

---

## 🆘 Contingency Plans

### If HF Space is down / cold
- PHP fallback (`localKeywordFallback`) activates automatically — no action needed
- Check HF Space status at: `https://huggingface.co/spaces/ashlogg/student-shield-official`
- Force rebuild: go to HF Space → Settings → Factory reboot

### If InfinityFree hosting is down
- Option A: Run locally + use Ngrok: `ngrok http 80` → update `app.baseURL` in `.env`
- Option B: Move to another free host (000webhost, Netlify Functions, etc.)

### If DB schema is out of sync on production
- Log into InfinityFree control panel → MySQL Databases → phpMyAdmin
- Run the missing ALTER TABLE statements manually (no SSH/CLI available)

### Azure VM (LEGACY — NO LONGER IN USE)
- The `docs/Deployment_Manuals/azure_vm_management_and_contingency.md` references an old Azure VM at `20.187.146.70` — **this is decommissioned**. Azure credits ran out. ML is now on Hugging Face permanently.

---

## 🔐 Security Notes

- Vent text is **AES-256 encrypted** before storing (`encryption.key` in `.env`)
- If `encryption.key` is missing/wrong, the inner try-catch in `VentController` falls back to `base64_encode` and logs an error — submissions are never silently lost
- CSRF protection is active (CI4 built-in) — AJAX requests include `X-CSRF-TOKEN` header + hidden form field
- Sessions use CI4 file-based session handler
- Rate limiting: 1 vent per 30 seconds per session (enforced in `VentController::submit`)

---

## 🛠️ Common Tasks Reference

| Task | How |
|---|---|
| Add a DB column locally | `D:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe -u root student_shield -e "ALTER TABLE ..."` |
| Add a DB column on production | InfinityFree phpMyAdmin → SQL tab |
| Check CI4 error logs (local) | `web-app-ci4/writable/logs/log-YYYY-MM-DD.log` |
| Run CI4 CLI (local only) | `cd web-app-ci4; php spark <command>` |
| Trigger HF deploy manually | Push any change to `ml_service/` on `main` branch |
| Test ML endpoint | `curl -X POST https://ashlogg-student-shield-official-2285456.hf.space/analyze-text -H "X-API-KEY: studentshield_secret_key_2026" -H "Content-Type: application/json" -d '{"raw_text":"test","student_id":"1"}'` |
| Run PowerShell git commands | Use `;` not `&&` as separator (PowerShell limitation) |
