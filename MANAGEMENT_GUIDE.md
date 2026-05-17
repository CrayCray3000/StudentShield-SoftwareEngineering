# StudentShield — Complete System Management Guide

Written for anyone who will maintain, update, or troubleshoot this project.
Last updated: May 2, 2026


---


# TABLE OF CONTENTS

    1.  How the System Works (Big Picture)
    2.  How a Vent Gets Processed (Step by Step)
    3.  How the Risk Scoring Works
    4.  How the Keyword Fallback Works
    5.  How Alerts Get Triggered
    6.  How Authentication Works
    7.  How Encryption Works
    8.  All Credentials and Where They Live
    9.  Local Development Setup
    10. How to Update the Web App (PHP)
    11. How to Update the ML Service (Python)
    12. How to Deploy the ML Service to Azure
    13. How to Manage the Azure VM
    14. How to Manage the Database
    15. How to Manage the Domain and Hosting
    16. How to Manage Email Alerts
    17. How to Manage SMS Alerts
    18. How to Add New Keywords
    19. How to Add a New Student or Counselor
    20. Git Workflow
    21. File Structure Reference
    22. Troubleshooting Guide
    23. Security Checklist


---


# 1. How the System Works (Big Picture)

StudentShield has three separate pieces that talk to each other:

    PIECE 1: Web App (PHP / CodeIgniter 4)
    ----------------------------------------
    This is the website that students and counselors use.
    Students submit vents and bully reports through it.
    Counselors review flagged entries on the admin dashboard.
    It runs on InfinityFree hosting at: http://studentshield.ct.ws

    PIECE 2: ML Service (Python / FastAPI)
    ----------------------------------------
    This is the AI brain. It receives vent text from the web app,
    runs it through multiple machine learning models, and returns
    a risk score (0-100%) and risk level (LOW/MEDIUM/HIGH/CRITICAL).
    It runs inside a Docker container on Azure VM: http://20.187.146.70

    PIECE 3: Database (MySQL)
    ----------------------------------------
    Stores all users, encrypted vents, bully reports, audit logs, and SMS logs.
    Hosted on InfinityFree: sql308.infinityfree.com

The flow is simple:

    Student types a vent
         |
         v
    Web App sends the text to ML Service via HTTP POST
         |
         v
    ML Service scores the text (0-100%) and returns risk level
         |
         v
    Web App also runs its own local keyword check (safety net)
         |
         v
    The HIGHER of the two scores wins
         |
         v
    Web App encrypts the text and saves it to the database
         |
         v
    If risk is CRITICAL, it sends SMS + Email alerts to parents


---


# 2. How a Vent Gets Processed (Step by Step)

When a student clicks "Submit" on the vent form, this is exactly what happens
inside VentController.php -> submit():

    Step 1: Rate Limit Check
    The system checks if the student submitted a vent in the last 30 seconds.
    If yes, it rejects the submission. This prevents spam.

    Step 2: Call ML Service
    The web app sends a POST request to http://20.187.146.70/analyze-text
    with the raw vent text and the student's ID.
    It waits up to 15 seconds for a response.
    If the ML service is down or slow, it silently falls back to 0% / LOW.

    Step 3: Local Keyword Fallback (Safety Net)
    Regardless of what the ML service returned, the web app also runs
    its own keyword-based scan using localKeywordFallback().
    If the local scan finds a HIGHER risk than the ML service returned,
    it overrides the ML score upward. It never downgrades the ML score.

    Step 4: Encrypt the Vent Text
    The raw text is AES-encrypted using the encryption.key in .env.
    The encrypted text is stored in hex format in the database.
    This means nobody can read the vents by looking at the database directly.

    Step 5: Save to Database
    The encrypted text, risk score, risk level, and timestamp are saved
    to the vent_logs table.

    Step 6: Trigger Alerts (if CRITICAL)
    If the final risk level is CRITICAL:
      - Check if the student checked "Hide from Parents" (domestic abuse safety)
      - If hidden: Send emergency alert ONLY to the counselor
      - If not hidden: Send SMS to parent phone + Email to parent email
      - Alert cooldown prevents duplicate alerts (1 per minute per recipient)

    Step 7: Return Response
    The student sees a confirmation message. They never see the risk score.


---


# 3. How the Risk Scoring Works

The ML service (main.py) uses a multi-stage pipeline:

    Stage 1: Keyword Matching
    Scans for 136+ crisis phrases in English, Filipino, Bisaya, and Ilocano.
    Instant CRITICAL if explicit suicide intent is found.

    Stage 2: Emotion Classification
    Uses the j-hartmann/emotion-english-distilroberta-base model.
    Detects emotions: sadness, anger, fear, disgust, surprise, joy.

    Stage 3: Distress Classification
    Uses ashlogg/student-shield-mdeberta (custom fine-tuned model).
    Fallback 1: SamLowe/roberta-base-go_emotions
    Fallback 2: unitary/toxic-bert

    Stage 4: Semantic Similarity
    Uses paraphrase-multilingual-MiniLM-L12-v2 to compare the vent
    against known crisis phrases using cosine similarity.

    Stage 5: FAISS Memory (Per-Student Context)
    Tracks each student's vent history using vector embeddings.
    If a student's recent vents show escalating distress, the score
    is boosted even if the current vent seems mild.

    Stage 6: Score Aggregation
    All signals are weighted and combined into a final 0-100% score.
    The score maps to a risk level:
      0-29%   = LOW
      30-59%  = MEDIUM
      60-84%  = HIGH
      85-100% = CRITICAL


---


# 4. How the Keyword Fallback Works

The PHP fallback in VentController::localKeywordFallback() is a safety net.
It exists because:
- The ML service might crash or be unreachable
- The ML might under-score Taglish slang it was not trained on

The fallback has these keyword tiers:

    CRITICAL EXTREME (99%) — Never downgraded, even if sarcastic
    Examples: suicide, kill myself, magpakamatay, laslas, bibigti, unalive

    CRITICAL PASSIVE (95%) — Downgraded to MEDIUM (60%) if sarcasm detected
    Examples: i wanna die, end it all, ayoko na mabuhay, gusto ko na mawala

    HIGH PHYSICAL (85%) — Never downgraded (physical violence)
    Examples: punched, choked, sinapak, binugbog, death threat

    HIGH EMOTIONAL (75%) — Downgraded to MEDIUM (45%) if sarcasm detected
    Examples: depressed, di ko na kaya, so sad, it hurts, scared, lonely

    MEDIUM (50%) — Downgraded to LOW (20%) if sarcasm detected
    Examples: bullied, ugly, bobo, toxic, cyberbully, pinahiya

    RELATIONSHIP (50%) — Downgraded to LOW (20%) if sarcasm detected
    Examples: break up, i miss her, love her so much, heartbroken, ghosted

Sarcasm detection: The system looks for joke markers (haha, lol, charot, jk)
and academic context (exam, homework, thesis). If a student says "kms this exam
is killing me haha" the suicidal keywords are downgraded because the context
is academic frustration + humor, not genuine crisis.


---


# 5. How Alerts Get Triggered

Alerts only fire when the final risk level is CRITICAL.

    SMS Alert:
    - Sent via an Android phone running an SMS gateway app on the local network
    - The phone must be on, connected to WiFi, and the app must be running
    - Default URL: http://192.168.1.3:8080/send-sms
    - Phone numbers are normalized from +63XXXXXXXXXX to 09XXXXXXXXXX

    Email Alert:
    - Sent via Gmail SMTP using an App Password
    - Goes to the parent_email registered for that student
    - Contains a professionally designed HTML email with wellness tips
    - The school logo is embedded inline so it shows even offline

    Counselor Emergency Alert:
    - Triggered when a student checks "Hide from Parents"
    - Sends SMS to the counselor hotline (09221234567)
    - Sends email to svbsmcounseling@gmail.com
    - This is the domestic abuse safety protocol

    Alert Cooldown:
    - After an alert is sent, the same recipient will not receive another
      for 1 minute (configurable in isAlertOnCooldown())
    - Alerts are logged in web-app-ci4/writable/logs/alerts.json


---


# 6. How Authentication Works

    Students:
    - Log in with name + passcode at /login
    - Passwords support both plaintext (legacy) and bcrypt
    - If a plaintext password is used, it auto-upgrades to bcrypt on login
    - "Remember Me" sets a secure cookie for 30 days
    - Session stores: user_id, name, role=0, isLoggedIn=true

    Counselors (role=1) and Super Admins (role=2):
    - Log in with name + passcode at /admin/login
    - After password check, a 6-digit OTP is emailed to their registered email
    - OTP expires in 5 minutes
    - They can check "Trust this device" to skip OTP for 30 days
    - Trusted device uses a separate cookie (admin_trusted_device)

    Route Protection:
    - All routes under the protected group require the auth filter
    - Students cannot access /admin/* routes
    - Counselors cannot access /student/* routes
    - The AuthFilter checks session + cookies on every request


---


# 7. How Encryption Works

    Vent text is encrypted before saving to the database:
    1. CodeIgniter's Encrypter service is initialized with the key in .env
    2. The raw text is encrypted with AES-256
    3. The encrypted bytes are converted to hex for safe database storage
    4. On the admin dashboard, the hex is decoded and decrypted for display

    The encryption key is in .env:
    encryption.key = hex2bin('785a21fa...')

    If you lose this key, all existing encrypted vents become unreadable.
    Never change this key unless you are okay with losing old data.

    Bully report descriptions are also encrypted the same way.


---


# 8. All Credentials and Where They Live

Everything is in one file: web-app-ci4/.env

    DATABASE
    Host:     sql308.infinityfree.com
    Database: if0_41428179_studentshield
    Username: if0_41428179
    Password: A0XMtOxIQVLBpLw
    Port:     3306

    EMAIL (Gmail SMTP)
    Host:     smtp.gmail.com
    User:     vonnchristianmielmeneses@gmail.com
    Password: vsmanuqipbmfasht  (this is an App Password, not the real one)
    Port:     587
    Crypto:   tls

    ML SERVICE
    URL:      http://20.187.146.70
    API Key:  svbsm-production-ml-key-v1

    AZURE VM (SSH)
    IP:       20.187.146.70
    Username: studentshield
    Password: VonnAsh12HairoMorale
    Port:     22

    SMS GATEWAY
    URL:      http://192.168.1.3:8080/send-sms

    ENCRYPTION KEY
    hex2bin('785a21faeb289ff35b887bd75168ceb78c950a7f1a3b8fc7d92a106e23b7e4d8')


---


# 9. Local Development Setup

    Requirements:
    - Laragon (includes Apache, PHP, MySQL)
    - PHP 8.1+ with extensions: curl, mbstring, openssl, mysqli
    - Git
    - Python 3.10+ (only if working on the ML service)

    Steps:
    1. Clone the repo:
       git clone https://github.com/mieell/student-shield-official.git "d:\laragon\www\student shield"

    2. Start Laragon (Apache + MySQL)
       The site will be at: http://student shield.test

    3. Make sure .env exists in web-app-ci4/ with all credentials filled in

    4. Import the database:
       Open phpMyAdmin, select your database, and run schema.sql

    5. Seed users:
       php seed_users.php

    6. Open in browser:
       http://student shield.test/


---


# 10. How to Update the Web App (PHP)

    Local Development:
    1. Edit files in web-app-ci4/
    2. Refresh the browser to see changes (no build step needed)
    3. PHP errors appear in web-app-ci4/writable/logs/

    Key files you will edit most often:
    - app/Controllers/VentController.php    (vent submission + risk logic)
    - app/Controllers/AdminController.php   (dashboard + incident management)
    - app/Controllers/AuthController.php    (login + OTP flow)
    - app/Controllers/BullyReportController.php  (bully report form)
    - app/Controllers/UserController.php    (user CRUD for super admin)
    - app/Config/Routes.php                 (URL routing)
    - app/Views/                            (all HTML templates)
    - .env                                  (config and credentials)

    Deploying to InfinityFree (production):
    1. Log into the InfinityFree control panel
    2. Open the Online File Manager or use FTP (FileZilla)
    3. Upload changed files to the correct directories under htdocs/
    4. InfinityFree does NOT support SSH or Composer
    5. All PHP dependencies are already in the vendor/ folder

    Important: After uploading, clear the CI4 cache:
    Delete everything inside writable/cache/ on the server


---


# 11. How to Update the ML Service (Python)

    The ML code lives in: ml_service/main.py

    To test locally:
    1. Create a virtual environment:
       cd ml_service
       python -m venv .venv
       .venv\Scripts\activate
       pip install -r requirements.txt

    2. Run the server:
       uvicorn main:app --host 0.0.0.0 --port 7860

    3. Test it:
       Open http://localhost:7860/docs for the Swagger UI
       Or run: python test_vent.py (change the URL to localhost first)

    After making changes, you must redeploy to Azure (see section 12).


---


# 12. How to Deploy the ML Service to Azure

    Option A — One-command deploy (recommended):

    cd "d:\laragon\www\student shield"
    python deploy_all.py

    This script automatically:
    1. Zips the ml_service folder (excluding .venv, __pycache__, optimizer.pt)
    2. Uploads the zip to the Azure VM via SFTP
    3. Stops and removes the old Docker container
    4. Cleans up old Docker images to free disk space
    5. Builds a new Docker image from the Dockerfile
    6. Starts the container with --restart unless-stopped
    7. Waits 15 seconds and runs a health check

    The whole process takes 10-20 minutes because pip installs torch (~2GB).

    Option B — Manual deploy:

    Step 1: Zip (local machine)
        python zip_ml.py

    Step 2: Upload (local machine)
        python sftp_upload.py

    Step 3: SSH in and build (on the VM)
        ssh studentshield@20.187.146.70
        # password: use the VM password from your secure password manager

        unzip -o ~/ml_service_deploy.zip -d ~/ml_service
        cd ~/ml_service
        sudo docker stop ml-api
        sudo docker rm ml-api
        sudo docker system prune -af --volumes
        sudo docker build -t studentshield-ml .
        sudo docker run -d --name ml-api --restart unless-stopped -p 80:7860 studentshield-ml

    Option C — Helper scripts (from local machine):

    python check_docker.py       Check if container is running
    python check_logs.py         View container logs
    python check_mem.py          Check VM memory usage
    python cleanup_docker.py     Clean up unused Docker images
    python test_vent.py          Test the ML API with a sample vent


---


# 13. How to Manage the Azure VM

    SSH Access:
    ssh studentshield@20.187.146.70
    Password: VonnAsh12HairoMorale

    Check if ML is running:
    sudo docker ps

    View logs:
    sudo docker logs ml-api
    sudo docker logs ml-api --tail 50
    sudo docker logs ml-api -f          (live follow, Ctrl+C to stop)

    Restart the ML service:
    sudo docker restart ml-api

    Free disk space:
    sudo docker system prune -af --volumes

    Check disk usage:
    df -h

    Check memory:
    free -m

    The VM needs at least 4GB RAM for the ML models.
    If it runs out of memory:
    1. Log into Azure Portal (portal.azure.com)
    2. Find the VM
    3. Stop it, resize to B2s or B2ms (4GB RAM minimum)
    4. Start it and SSH in to restart Docker

    The container has --restart unless-stopped so it will auto-restart
    if the VM reboots or Docker restarts. But if the VM itself stops
    (Azure maintenance, billing, etc.), you need to manually start it
    from the Azure Portal.


---


# 14. How to Manage the Database

    Access:
    Log into InfinityFree control panel -> phpMyAdmin

    Connection details:
    Host:     sql308.infinityfree.com
    Database: if0_41428179_studentshield
    Username: if0_41428179
    Password: A0XMtOxIQVLBpLw

    Tables:
    users           Student and counselor accounts
    vent_logs       Encrypted vent entries with risk scores
    bully_reports   Bullying incident reports with evidence files
    audit_logs      Every admin action (resolve, delete, verify, etc.)
    sms_logs        Record of SMS alerts sent

    Important notes:
    - raw_text in vent_logs is AES-encrypted hex. It looks like garbage.
      This is normal. The app decrypts it when displaying on the dashboard.
    - deleted_at columns are for soft deletes. Records are never truly deleted.
    - audit_logs records who did what, when, from which IP.

    To add a column:
    ALTER TABLE table_name ADD COLUMN column_name TYPE DEFAULT value;

    To backup:
    Use phpMyAdmin Export (SQL format) from the InfinityFree panel.


---


# 15. How to Manage the Domain and Hosting

    Current domain: studentshield.ct.ws (free subdomain from InfinityFree)
    Hosting: InfinityFree (free PHP hosting)
    Control Panel: https://dash.infinityfree.com

    To use a custom domain:
    1. Buy a domain from Namecheap, GoDaddy, Porkbun, etc.
    2. In InfinityFree, go to Addon Domains and add your domain
    3. At your registrar, set nameservers to:
       ns1.byet.org
       ns2.byet.org
    4. Wait 24-48 hours for DNS propagation
    5. Update app.baseURL in .env to your new domain
    6. Enable SSL in InfinityFree for HTTPS

    InfinityFree limitations:
    - No SSH access
    - No Composer or CLI tools
    - No cron jobs
    - File size limit: 10MB
    - Daily hit limit: 50,000 visits
    - Occasional downtime on free tier


---


# 16. How to Manage Email Alerts

    Email goes through Gmail SMTP with an App Password.

    If emails stop working:
    1. Log into Gmail: vonnchristianmielmeneses@gmail.com
    2. Go to Google Account > Security > 2-Step Verification
    3. Scroll to App Passwords
    4. Generate a new password
    5. Update email.SMTPPass in .env

    Emails are sent to:
    - Parent email (parent_email column in users table) for vent alerts
    - svbsmcounseling@gmail.com for counselor emergency alerts
    - Counselor's registered email for OTP codes

    The email templates are inline HTML in VentController.php.
    Search for "triggerEmailAlert" to find and edit them.


---


# 17. How to Manage SMS Alerts

    SMS uses a local Android phone as a gateway.

    Setup:
    1. Install "SMS Gateway" app from Play Store on an Android phone
    2. Insert a SIM card with text credits (Globe, Smart, etc.)
    3. Connect the phone to the same WiFi network
    4. Note the phone's IP address (e.g., 192.168.1.3)
    5. The app exposes an HTTP endpoint at: http://<phone-ip>:8080/send-sms

    Configure in .env:
    sms.GatewayUrl = http://192.168.1.3:8080/send-sms

    SMS only works when:
    - The phone is powered on
    - The phone is connected to the same network
    - The SMS gateway app is running in the foreground
    - The SIM has text credits or an unlimited plan

    Phone numbers are normalized:
    +639XXXXXXXXX -> 09XXXXXXXXX


---


# 18. How to Add New Keywords

    PHP side (VentController.php, localKeywordFallback function):
    - Open: web-app-ci4/app/Controllers/VentController.php
    - Find the function: localKeywordFallback
    - Add your new keyword to the appropriate array:
      critical_extreme = explicit suicide (99%, never downgraded)
      critical_passive = passive ideation (95%, downgraded if sarcasm)
      high_physical = violence (85%, never downgraded)
      high_emotional = emotional distress (75%, downgraded if sarcasm)
      medium = verbal abuse / cyberbullying (50%, downgraded if sarcasm)
      relationship = breakup / rejection (50%, downgraded if sarcasm)

    Python side (ml_service/main.py):
    - Open: ml_service/main.py
    - Find CRISIS_PHRASES (for CRITICAL keywords)
    - Find DISTRESS_PHRASES (for HIGH/MEDIUM keywords)
    - Add your keyword to the correct list
    - Redeploy with: python deploy_all.py

    After adding keywords on either side, test with:
    python test_vent.py
    (Edit the raw_text in test_vent.py to test your new keywords)


---


# 19. How to Add a New Student or Counselor

    Option A — Through the Super Admin dashboard:
    1. Log in as Super Admin at /admin/login
    2. Go to the Users tab
    3. Click "Add User"
    4. Fill in: name, student_id, passcode, parent_phone, parent_email, section, adviser
    5. Set role: 0 = Student, 1 = Counselor, 2 = Super Admin

    Option B — Direct database insert:
    INSERT INTO users (student_id, name, passcode, role, parent_phone, parent_email, section, adviser_name)
    VALUES ('2024-0001', 'Juan Dela Cruz', 'password123', 0, '+639171234567', 'parent@email.com', 'Section A', 'Mr. Santos');

    For counselors, set role = 1 and put their email in parent_email
    (this is where OTP codes are sent).

    Option C — Bulk seeding:
    Edit seed_users.php and run: php seed_users.php


---


# 20. Git Workflow

    The repo is at: https://github.com/mieell/student-shield-official

    To push changes:
    cd "d:\laragon\www\student shield"
    git add .
    git commit -m "describe what you changed"
    git push origin main

    To pull latest:
    git pull origin main

    Important: The .env file is in .gitignore.
    Credentials are NOT pushed to GitHub.
    If you set up on a new machine, you need to create .env manually.


---


# 21. File Structure Reference

    student shield/
    |
    |- web-app-ci4/                  PHP web application
    |  |- app/
    |  |  |- Controllers/
    |  |  |  |- VentController.php        Vent submission + risk scoring + alerts
    |  |  |  |- AdminController.php       Dashboard, resolve/delete/verify actions
    |  |  |  |- AuthController.php        Login, OTP, logout, remember me
    |  |  |  |- BullyReportController.php Bully report form + evidence upload
    |  |  |  |- UserController.php        User CRUD (super admin only)
    |  |  |- Config/
    |  |  |  |- Routes.php                All URL routes
    |  |  |  |- Filters.php               Auth filter registration
    |  |  |- Filters/
    |  |  |  |- AuthFilter.php            Session/cookie auth + role-based access
    |  |  |- Views/                       HTML templates
    |  |  |- Models/                      Database models
    |  |- public/                         Web root (index.php, CSS, JS, images)
    |  |- writable/logs/                  App logs + alerts.json
    |  |- .env                            All config and credentials
    |
    |- ml_service/                        Python AI microservice
    |  |- main.py                         All ML logic, API endpoints, scoring
    |  |- Dockerfile                      Docker build instructions
    |  |- requirements.txt                Python dependencies
    |  |- data/student_vents.csv          Training dataset
    |  |- models/                         Fine-tuned model weights
    |  |- faiss_memory/                   Per-student vector memory (auto-created)
    |
    |- docs/                              Architecture diagrams, DFDs, ERDs
    |- schema.sql                         Database table definitions
    |- deploy_all.py                      One-command ML deploy to Azure
    |- zip_ml.py                          Zips ml_service for upload
    |- sftp_upload.py                     Uploads zip to Azure VM
    |- check_docker.py                    Checks Docker container status
    |- check_logs.py                      Views Docker container logs
    |- check_mem.py                       Checks VM memory usage
    |- cleanup_docker.py                  Frees disk space on VM
    |- test_vent.py                       Tests ML API with sample vent
    |- seed_users.php                     Seeds initial accounts
    |- import_db.php                      Imports schema


---


# 22. Troubleshooting Guide

    PROBLEM: ML service returns 0% for everything
    CAUSE:   Docker container crashed or is running old code
    FIX:     SSH in, run "sudo docker ps" to check. Redeploy with deploy_all.py

    PROBLEM: "Connection refused" when submitting vents
    CAUSE:   ML service is down or Azure VM is stopped
    FIX:     Check Azure Portal if VM is running. SSH in, restart Docker.

    PROBLEM: Emails are not sending
    CAUSE:   Gmail App Password expired or 2FA was turned off
    FIX:     Generate a new App Password and update email.SMTPPass in .env

    PROBLEM: OTP code never arrives
    CAUSE:   Counselor has no email in parent_email column
    FIX:     Add their email to the users table, parent_email field.
             Or check the CI4 logs: writable/logs/ for the OTP value.

    PROBLEM: SMS is not sending
    CAUSE:   Phone is off, disconnected, or gateway app is closed
    FIX:     Reconnect the phone and reopen the SMS gateway app

    PROBLEM: Vent text shows as garbage in phpMyAdmin
    CAUSE:   Normal — vent text is AES-encrypted at rest
    FIX:     View it through the admin dashboard where it is decrypted

    PROBLEM: Docker build fails with "no space left on device"
    CAUSE:   Old Docker images filling up the Azure VM disk
    FIX:     Run "sudo docker system prune -af --volumes" then rebuild

    PROBLEM: Website shows PHP errors
    CAUSE:   CI_ENVIRONMENT is set to "development" instead of "production"
    FIX:     Set CI_ENVIRONMENT = production in .env

    PROBLEM: "Class not found" on the live site
    CAUSE:   Missing files on InfinityFree
    FIX:     Re-upload the full web-app-ci4 folder via FTP

    PROBLEM: Database connection timeout
    CAUSE:   InfinityFree MySQL downtime (free hosting)
    FIX:     Wait and try again. Free tier has occasional outages.

    PROBLEM: Student says "it hurts" but gets 0%
    CAUSE:   ML service is down AND the local fallback is missing that keyword
    FIX:     Add the keyword to the appropriate array in localKeywordFallback()

    PROBLEM: VM rebooted and ML is not running
    CAUSE:   Docker was not set to auto-restart
    FIX:     SSH in and run: sudo docker start ml-api
             Or redeploy with --restart unless-stopped flag


---


# 23. Security Checklist

Before any public deployment, verify:

    [ ] CI_ENVIRONMENT = production in .env
    [ ] encryption.key is set and has not been changed since first use
    [ ] ML_API_KEY matches between .env and ml_service/main.py
    [ ] Gmail App Password is valid and 2FA is enabled
    [ ] Rate limiting is active (30-second cooldown per student)
    [ ] OTP is required for counselor login
    [ ] Audit logging is enabled (audit_logs table)
    [ ] HTTPS is enabled on the domain
    [ ] .env is in .gitignore (credentials not on GitHub)
    [ ] Passwords are bcrypt hashed (auto-upgraded on login)
    [ ] Vent text is AES-encrypted before database storage
    [ ] Soft deletes are used (deleted_at, not DELETE FROM)
    [ ] Alert cooldown prevents notification spam
    [ ] "Hide from Parents" protocol works for domestic abuse cases
