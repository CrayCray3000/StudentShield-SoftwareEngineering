# StudentShield: Paper vs. Current System Gap Analysis

> Last verified against the repository and the public unauthenticated production pages on September 5, 2026. The implementation is the operational source of truth when older thesis prose conflicts with this document.

## Current Production Architecture

StudentShield is a two-cloud application:

1. The CodeIgniter 4 web application runs on InfinityFree behind Cloudflare at `https://studentshield.app`.
2. The FastAPI/PyTorch ML service runs in the Hugging Face Space `ashlogg/student-shield-official`.
3. The web application stores accounts, encrypted submissions, cases, and audit data in MySQL.
4. Jitsi Meet supplies one-to-one student/counselor video rooms.
5. Environment-configured SMTP and SMS gateways deliver alerts.

Production has no SSH or runtime CLI access. Web changes deploy by FTP through GitHub Actions, while ML changes deploy independently to Hugging Face.

## Implemented

| Capability | Current implementation |
|---|---|
| Student portal | Public login at `/` and `/login`; approved students reach `/student/dashboard`. |
| Registration approval | `/register` creates an inactive role-0 account; counselor/admin approval is required before login. |
| Terms and privacy acceptance | Registration requires the displayed Terms and Data Privacy checkbox. |
| Safe Space venting | Authenticated students submit encrypted vents through `/student/vent`. |
| ML risk analysis | FastAPI combines emotion classification, fine-tuned distress detection, multilingual rules, and contextual trajectory logic. |
| Local safety fallback | PHP evaluates the Tagalog, Taglish, Bisaya, Ilocano, and English keyword fallback when the ML response is unavailable or invalid. |
| Four risk tiers | LOW 1–30%, MEDIUM 31–60%, HIGH 61–85%, and CRITICAL 86–100%. |
| Clinical alert separation | CRITICAL alerts counselors and normally parents; HIGH alerts counselors only. The parent-hiding safety option is honored for CRITICAL vents. |
| Encryption at rest | CodeIgniter's OpenSSL encrypter uses AES-256-CTR for vent text and bullying-report details. |
| Bullying reports | Students can file self/bystander reports with encrypted details and optional image/video evidence. |
| Counselor-gated parent alerts | Report intake alerts counselors first. Parent notifications occur only after staff selects Verify & Alert Parents. |
| False-report controls | Rejected reports create progressive strikes; three strikes suspend reporting privileges until staff unlocks the account. |
| Staff OTP | Counselors and super admins share `/admin/login` and use six-digit email OTP verification with expiry and attempt limits. |
| Unified staff dashboard | Counselors and super admins monitor vents, incidents, risk charts, alert logs, and pending meeting requests. |
| Case summary | Staff can inspect risk history, vents, incidents, notification logs, notes, status history, and a unified case timeline. |
| Case notes and statuses | Counselors can add notes and move cases through the defined case-management states. |
| One-to-one video meetings | Students request sessions; staff start a unique Jitsi room; both roles join through embedded or linked Jitsi clients. |
| User management | Super admins create, edit, delete, and unlock accounts. |
| Audit logs | Login and administrative/case actions are recorded in the audit log. |
| Archive and restore | Vent and incident records support soft deletion, restoration, and bulk operations. |
| Printable case report | The case-summary view includes print/PDF styling and an official confidential report layout. |

## Partially Implemented

| Capability | Current limitation |
|---|---|
| Guardian consent | The registration checkbox captures acceptance as a submission requirement, but there is no separate guardian identity, signature, consent record, version, or timestamp. |
| Parent participation | Parents receive SMS/email notifications but have no account, portal, or acknowledgement workflow. |
| Student report tracking | Report status exists in the database, but students do not have a report-history/status page. |
| Counselor assignment | Case summaries and case statuses exist, but a case cannot be assigned to a named counselor with ownership and workload tracking. |
| Trajectory durability | The ML service writes FAISS/index metadata to its local filesystem. Durable 14-day history depends on the Hugging Face Space having persistent storage and is not guaranteed by the code alone. |
| Counselor recommendations | Risk reasons and trends are available, but there is no structured, counselor-facing intervention recommendation engine. |
| Grade/year analytics | Grade data is collected, but the main dashboard does not provide a complete grade/year analytics filter. |
| Alert observability | Several alert logs exist, but delivery behavior and logging are split between database tables and `writable/logs/alerts.json`. |
| ML availability | Hugging Face cold starts can delay or prevent inference; PHP fallback preserves basic triage but not full neural/trajectory analysis. |

## Not Implemented

| Capability described or implied by older papers | Current status |
|---|---|
| Teacher role and teacher dashboard | No teacher role exists. Roles are student (`0`), counselor (`1`), and super admin (`2`). |
| Parent/guardian login | No parent role, authentication flow, or dashboard exists. |
| Student/staff live chat | No persistent two-way chat or messaging module exists. Jitsi meetings are the only real-time communication feature. |
| Automated 24-hour resolution acknowledgement | No scheduler or parent acknowledgement link closes the alert loop automatically. |
| Bullying hotspot heatmap | The current report flow does not extract normalized locations or render a location heatmap. |
| Case workload assignment | There is no queue ownership, assignment history, or counselor workload view. |
| ML accuracy feedback dashboard | Counselors cannot label ML predictions as correct/incorrect through a dedicated feedback and evaluation interface. |

## Documentation Corrections

- Older documents that reference the Azure VM describe a decommissioned deployment. Hugging Face Spaces is the current ML runtime.
- The active encryption configuration is OpenSSL AES-256-CTR, not Sodium AES-256-GCM.
- The PHP fallback does not always compete with the ML score. It runs fully when the ML service response is unavailable or invalid; explicit suicide phrases also hard-override a valid ML response.
- Student authentication uses name and password, not the generated `students.student_id`.
- Counselors do not have a separate dashboard implementation. `/counselor/*` redirects to the unified `/admin/*` routes.
- Parent alerts for bullying are not sent at report intake. They are counselor-gated through report verification.
- Video conferencing is implemented through Jitsi Meet and is no longer a paper-only feature.

## Open Security and Reliability Work

These items describe current code and should not be presented as completed safeguards:

1. Remove the public `/debug-meeting` route. It discloses database structure and record details and performs a temporary write.
2. Rotate credentials previously stored in tracked documentation or hard-coded defaults, purge them from Git history, and rely exclusively on deployment secrets.
3. Enable and test the global CodeIgniter CSRF filter. Rendering CSRF fields does not enforce validation while the filter is disabled.
4. Re-enable TLS peer and hostname verification for calls to the ML service.
5. Stop logging plaintext OTP values.
6. Fix the undefined `$riskPercent` variable in the HIGH-risk counselor message.
7. Consolidate the duplicate Hugging Face deployment workflows.
8. Replace request-time schema alterations with reviewed migrations or documented phpMyAdmin deployment steps.

## Authoritative Code References

- Routes and role entry points: `web-app-ci4/app/Config/Routes.php`
- Authentication and registration: `web-app-ci4/app/Controllers/AuthController.php`
- Vent analysis and alerts: `web-app-ci4/app/Controllers/VentController.php`
- Bullying intake: `web-app-ci4/app/Controllers/BullyReportController.php`
- Staff triage: `web-app-ci4/app/Controllers/AdminController.php`
- Case aggregation: `web-app-ci4/app/Controllers/CaseSummaryController.php`
- Meetings: `web-app-ci4/app/Controllers/VirtualMeetingController.php`
- ML pipeline: `ml_service/main.py`
- Database baseline: `schema.sql`
