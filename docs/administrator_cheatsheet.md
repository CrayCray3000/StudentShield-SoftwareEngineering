# StudentShield - Administrator Terminal Cheatsheet

This document contains quick, essential terminal commands to manage the StudentShield application natively on Windows (via PowerShell or Laragon Terminal). These commands are extremely useful for bypassing security lockouts during testing, resetting the database, and running the background services.

---

## 🛑 1. Security & Lockout Overrides

During testing, you or the panelists might intentionally trigger the **Brute-Force Rate Limiter** by entering the wrong password 5 times. This completely blocks the IP address from logging in for 15 minutes.

Here is how to instantly bypass and delete the security block:

**Option A: The Official Framework Wipe (Clears all CI4 Cache)**
```powershell
# Run this inside the web-app-ci4 folder
php spark cache:clear
```

**Option B: The "Hard Wipe" (Destroys the physical tracking files)**
If option A fails because of our custom security architecture, use this PowerShell command to directly shred the tracked IP JSON files:
```powershell
# Instantly deletes all rate-limit bans
Remove-Item -Path "d:\laragon\www\student shield\web-app-ci4\writable\rate_limits\*" -Recurse -Force
```

---

## 🚀 2. Server & Application Initialization

These commands launch the actual services required to run StudentShield locally or on a VPS.

**Running the PHP Backend (CodeIgniter 4)**
```powershell
cd "d:\laragon\www\student shield\web-app-ci4"
php spark serve
```
*(This starts the web server, usually at `http://localhost:8080`)*

**Running the Machine Learning Microservice (Python FastAPI)**
```powershell
cd "d:\laragon\www\student shield\ml_service"
# Activate the virtual environment
.\myenv\Scripts\activate
# Start the AI Server
uvicorn main:app --reload --port 8000
```
*(This starts the AI Brain that analyzes vents securely, isolated on port 8000)*

---

## 💾 3. Database Resets & Migrations

If the database gets messy during panel testing and you want to completely wipe the data and start fresh with blank tables (WARNING: Destructive!):

**Wipe entirely and rebuild the schema from scratch:**
```powershell
cd "d:\laragon\www\student shield\web-app-ci4"
php spark migrate:refresh
```

**Just run new migrations (Safe, non-destructive):**
```powershell
php spark migrate
```

---

## 🧹 4. System Maintenance

If the server behaves weirdly or views don't seem to update, clearing the session data or logs can help.

**Destroy all active user sessions (Forces everyone to log out):**
```powershell
Remove-Item -Path "d:\laragon\www\student shield\web-app-ci4\writable\session\*" -Recurse -Force
```

**Clear gigabytes of old system error logs:**
```powershell
Remove-Item -Path "d:\laragon\www\student shield\web-app-ci4\writable\logs\*" -Recurse -Force
```
