# StudentShield: Paper vs System Gap Analysis

> Based on `StudentShield Capstone.pdf` (86 pages, May 2026) vs actual codebase in `web-app-ci4/` and `ml_service/`.

---

## ✅ FULLY IMPLEMENTED (Paper says it, system has it)

| Feature | Evidence in Code |
|---|---|
| Student Vent / Safe Space portal | `VentController.php`, `vent_form.php`, `vent_logs` table |
| Bullying Incident Report (with evidence upload) | `BullyReportController.php`, `report_bullying.php` |
| ML-based emotional distress detection (mDeBERTa) | `ml_service/main.py` |
| 14-day FAISS historical trajectory tracking | `ml_service/main.py` + `AddTrajectoryToVentLogs` migration |
| Severity levels (Low, Medium, High, Critical) | `ml_service/main.py`, `VentController.php` |
| Automated SMS alert to parents via local SMS Gateway | `VentController.php::triggerSmsAlert()`, `AdminController.php::sendLocalSms()` |
| Automated Email alert to parents | `VentController.php`, `BullyReportController.php` |
| Counselor SMS + Email alert on new bullying report | `BullyReportController.php` lines 166–170 |
| Two-Factor Authentication (OTP via email) for counselors/admins | `AuthController.php` — full OTP flow, 5-attempt lockout |
| Super Admin role (role=2) + Counselor role (role=1) | `AuthController.php`, `AdminController.php` |
| Student role (role=0) — name-only login | `AuthController.php` |
| Admin dashboard with vent log monitoring | `admin_dashboard.php`, `AdminController.php` |
| Counselor: archive, restore, bulk-delete vent entries | `VentController.php` |
| Counselor: mark as Resolved | `VentController.php` |
| Audit logging (every admin action logged) | `AuditLogModel.php`, `admin_audit_logs.php`, `CreateAuditLogsTable` migration |
| Fake report protection (no immediate parent SMS on submission) | `BullyReportController.php` lines 107, 152 |
| Parent SMS triggered only AFTER counselor verifies report | `AdminController.php::verifyIncident()` |
| Rate limiting on report submission | `RateLimitFilter.php` |
| Anonymous-style venting (system knows identity, public display is pseudonymous) | `VentController.php` |
| Student profile view for counselors | `CaseSummaryController.php` |
| SMS logs + Email logs table | `SMSLogModel.php`, `CaseSummaryController.php` |
| AES-256 encryption mentioned | Referenced in paper; environment-secured |
| Section + Adviser fields on student profiles | `AddSectionAndAdviserToUsers` migration |
| Behavioral pattern detection (14 patterns via regex) | `ml_service/main.py` |
| Student dashboard | `student_dashboard.php`, `StudentDashboardController.php` |
| Case summary / student profile aggregation | `CaseSummaryController.php` |
| **PDF export / print report** | `case_summary.php` — browser `window.print()` with full print stylesheet, official header, confidential footer, and "Print / Export PDF" button |

---

## ⚠️ PARTIALLY IMPLEMENTED (mentioned in paper but incomplete in system)

| Feature | What Paper Says | What's Actually There | Gap |
|---|---|---|---|
| **Parent/Guardian alerting** | Parents receive SMS + Email for both vent AND bullying alerts | SMS/Email sends to `parent_phone` field | **No parent portal or login** — parents only get SMS/email pushes, they can't log in or view anything |
| **Data Privacy / RA 10173** | Paper cites Republic Act 10173 (Data Privacy Act), mentions guardian consent model must be applied before registration | System has no guardian consent screen, terms and conditions, or checkbox | **Consent gate UI missing (no guardian consent screen, terms and conditions too)** |
| **Report status tracking** | Paper implies students can see the status of their reports | `bully_reports` table has a status column | **No UI for students to check report status** — student dashboard doesn't show report history or status |
| **Behavioral trajectory** | FAISS tracks 14-day window and can escalate severity | Migration exists, ML service has code | Trajectory data written to DB but **counselor dashboard doesn't visualize the 14-day graph/trend** |
| **Escalation workflow** | Paper says risk escalation triggers counselor alert automatically | VentController triggers SMS at CRITICAL | Only CRITICAL triggers auto-alert; **no automated escalation path documented per role** (e.g. Medium → counselor queue) |
| **Counselor assignment** | Paper mentions counselors can assign/manage cases | Case summary exists | **No case assignment UI** — can't assign a report to a specific counselor |

---

## ❌ NOT IMPLEMENTED (paper says it, system does NOT have it)

| Feature | What Paper Says | Status |
|---|---|---|
| **Teacher role / Teacher accounts** | Paper lists "Teachers" as a user actor in the Use Case Diagram. Teachers have their own flowchart (Figure 7 mentions Counselors Flowchart; teacher interactions are described) | **No teacher role exists** in code. Only role=0 (student), role=1 (counselor), role=2 (super_admin). No teacher login, no teacher-specific views. |
| **Parent/Guardian portal or login** | Paper explicitly describes: "Parents/Guardians" as actors. Parents are supposed to be able to log in and view notifications/reports about their child | **No parent login exists.** Parents only receive SMS/email. No `parent` role, no parent dashboard, no parent authentication. |
| **Video conferencing (1-on-1)** | Paper states: *"The system includes an online video conferencing feature limited to one-on-one sessions between a student [and counselor]"* | **Completely absent.** No Zoom/Jitsi/WebRTC integration exists anywhere in codebase. |
| **Live chat / in-app messaging** | Paper references a "Safe Space" portal with a conversational/chat-like UI that encourages usage | **No chat feature.** Paper's own limitations section notes *"Live chat and interactive dialogue are not supported"* — but the portal concept implies a messaging UI that was planned |
| **Recommendations engine** | Paper mentions the system should provide counselor-facing recommendations based on student risk | **No recommendation output UI.** ML service returns a risk level but does not generate intervention recommendations shown to counselors |
| **Grade level / Year level as filter** | Paper describes filtering students by grade/year level in dashboards | **Section field exists** but **no grade level field**, and **no dashboard filter by grade/year level** |
| ~~**PDF export / report generation**~~ | ✅ CONFIRMED PRESENT — `case_summary.php` has a "Print / Export PDF" button with full `@media print` styling, official header, and confidential footer. Removed from this list. |
| **Guardian consent & Terms** | Paper: *"user registration, mood tracking, and notification for parents must be suspended until guardian consent is secured"* | **No consent mechanism.** Students can register and use the system without any guardian consent or terms and conditions being logged/agreed to. |
| **Escalation notification to school admin** | Paper: *"SMS alerts via local SMS Gateway…for notifying parents and school administrators at once"* | Admin is notified via dashboard but **no SMS is sent to school administrators** — only counselor and parents receive SMS |
| **Pilot test / accuracy tracking** | Paper mentions verifying AI flag accuracy through a pilot test with SVBSM admin | **No accuracy tracking dashboard or pilot test module** exists |

---

## 📁 File Cleanup Done

- Deleted: `Capstone Project 1 - Updated.docx` (duplicate)
- Kept: `StudentShield Capstone.pdf` ← **this is the original paper**
- Kept: `StudentShield Capstone.docx` ← editable version

> Temp files `_capstone_extracted.txt`, `_capstone_clean.txt`, `_capstone_readable.txt` and `analyze_paper.py` in `ml_service/` — safe to delete after this review.
