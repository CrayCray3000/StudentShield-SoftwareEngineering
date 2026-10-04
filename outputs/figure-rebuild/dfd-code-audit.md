# StudentShield DFD source audit

Checked 4 October 2026 against the current local source. This is not independent verification of the deployed version.

## Boundary and notation

The boundary is the PHP web application and its retained application data. The Python screening API is external to this boundary. The paper currently uses context/Level 0 for the whole system and Level 1 for its major processes; retain that convention explicitly. See `dfd-research.md` for primary-source notation and balancing rules.

## Corrections required in existing Figures 4, 6, and 7

- The screening request contains current `raw_text`, `student_id`, and the previous 14 days of risk labels and timestamps read from `vent_logs`. It does not transmit old vent text or query a FAISS memory. Source: `VentController.php::submit`, `main.py::TextPayload`, `risk_history.py::evaluate_risk_history`.
- The `/analyze-text` response exposes `risk_score`, `risk_level`, `flag_reason`, `trajectory`, and `escalation_probability`. Emotion labels are not returned by the current endpoint. Student-selected moods are saved from the form's `emotions` field. Do not label the returning API arrow “risk + emotions.” Source: `main.py::MLResponse`, `main.py::analyze_text`, `VentController.php::submit`.
- Trend calculation is deterministic over risk labels mapped to numerical levels. The field named `escalation_probability` is a heuristic score, not evidence of a calibrated predictive probability. Do not imply validated prediction accuracy.
- Google Meet is a staff-created URL stored by the application and returned to authorized users. No direct Google Meet API, video, attendance, or call-ended feed is implemented. Source: `GoogleMeetSessions.php`, `VirtualMeetingController.php`.
- `VentController::logAlert` writes `writable/logs/alerts.json`. The mere presence of `sms_logs` and `email_logs` in schema/read paths does not prove all outgoing messages are saved there. Do not assert universal message logging or successful delivery.
- Incident submission saves `bully_reports` with pending-review status and optional evidence path. It sends a receipt to the stored reporter email, which is the parent email for student reporters, and attempts counselor intake notifications. Staff verification and false-report handling have separate notification paths. Source: `BullyReportController.php`, `AdminController.php`.
- Staff case work reads users, vents, reports, notes, status history, and meeting records. It writes notes/status and session records. Account and selected case actions write audit events; not every read or action is audited. Source: `CaseSummaryController.php`, `AdminController.php`, `BaseController::logAudit`, `UserController.php`.
- The parent-hiding choice still suppresses the parent notification branch in the local vent controller, including critical cases. Describe current behavior without claiming compliance with the contradictory repository policy; do not change application behavior during this paper task.

## Logical store mapping for the redraw

| Store | Implementation |
|---|---|
| Account and profile records | users, students, counselors, admins |
| Vent records | vent_logs, including stored risk history and student-selected moods |
| Incident records and evidence | bully_reports and associated uploaded evidence files |
| Case and session records | counselor_notes, case_status_history, virtual_meetings |
| Audit records | audit_logs |
| Vent alert records | writable/logs/alerts.json |

Do not use hosting providers, Cloudflare, programming languages, or encryption algorithms as logical data stores or business processes. Their place is the architecture/security description.

## Balanced redraw inventory (paper convention)

Use **Level 0 = context** and **Level 1 = major processes** throughout this paper. Process 0 is “StudentShield Web Application.” The boundary includes PHP application behavior and its application stores; ML screening is external. This is a logical model: email/SMS transport is abstracted into notification exchanges with recipients, not expanded into provider infrastructure. Notification arrows mean messages addressed to recipients, not proof of delivery.

### Level 0 boundary flows, with Level 1 allocation

Each row is a directed data flow; retain these labels in both diagrams. The P identifiers show where the context flow terminates/originates in Level 1. “Staff” below means the separate Counselor and Administrator entities, subject to implemented role permissions; it need not become an extra entity.

| External source | Destination | Data label | Level 1 process |
|---|---|---|---|
| Student | System | Registration and login data | P1 |
| System | Student | Registration and access results | P1 |
| Counselor / Administrator | System | Credentials and verification code | P1 |
| System | Counselor / Administrator | Access results | P1 |
| Administrator | System | Account changes and approval decisions | P1 |
| System | Administrator | Account records and update results | P1 |
| Student | System | Vent text, selected moods, and privacy choice | P2 |
| System | Student | Vent submission result | P2 |
| System | ML screening service | Current vent, student ID, and risk history | P2 |
| ML screening service | System | Risk and trajectory assessment | P2 |
| Student / reporting staff | System | Incident details and evidence | P3 |
| System | Student / reporting staff | Incident submission result | P3 |
| Counselor / Administrator | System | Incident review decisions | P3 |
| System | Counselor / Administrator | Incident review results | P3 |
| Student | System | Session request, cancellation, and access request | P4 |
| System | Student | Session status and authorized meeting link | P4 |
| Counselor / Administrator | System | Case notes, status changes, and session details | P4 |
| System | Counselor / Administrator | Case update results and session access details | P4 |
| System | Counselor | Triage notices and verification messages | P5 |
| System | Administrator | Verification and account messages | P5 |
| System | Parent | Wellness and incident notices | P5 |
| Counselor / Administrator | System | Monitoring and case queries | P6 |
| System | Counselor / Administrator | Dashboards, case summaries, and activity records | P6 |

Context arrows may aggregate closely related rows only if the caption/data dictionary lists the constituents. Keep direction separate: queries are inputs and summaries are outputs. Do not imply a parent portal or parent input flow; neither is evidenced here. The student vent result can contain a risk label for AJAX submission, but it is not the full ML assessment. Reporter receipts go to the configured reporter email; for student reporters this resolves to the parent email and is included under Parent incident notices.

### Level 1 processes (six)

| ID | Logical process | Internal data flows |
|---|---|---|
| P1 / 1.0 | Manage accounts and access | D1→P1 account/profile records; P1→D1 registration, account, and access updates; P1→D5 selected account/access audit events; P1→P5 verification and account-notice data. Account unlock also updates false-report flags in D3. |
| P2 / 2.0 | Record and assess vents | D2→P2 recent risk labels and timestamps; P2→D2 vent record, student-selected moods, and resulting risk fields; P2↔ML carries the two boundary flows above; P2→P5 risk-notice data including student identity, assessed risk, and privacy choice. Local keyword evaluation is part of P2, not an external service. |
| P3 / 3.0 | Manage incident reports | D1→P3 identity/contact data; P3↔D3 submitted/retrieved reports, evidence, and review/status updates; P3→D1 account lock changes for repeated false reports; P3→D5 selected incident audit events; P3→P5 receipt and incident-notice data. |
| P4 / 4.0 | Manage cases and counseling sessions | D1→P4 student/assignment details; D4→P4 existing notes, case status, and sessions; P4→D4 note/status/session changes; P4→D5 selected case audit events. Vent review/restore/delete actions read/update D2 and write D5. Grouping cases and sessions is valid because sessions are part of case support, but only actual staff-provided Meet links are modeled. |
| P5 / 5.0 | Prepare and send notifications | P1/P2/P3→P5 message data; D1→P5 recipient contact details; D6→P5 previous vent alert attempts for cooldown; P5→D6 vent alert attempts and optional staff OTP email attempt records. P5→P1 OTP delivery outcome (used by authentication fallback). P5 produces the recipient boundary messages above. |
| P6 / 6.0 | Provide monitoring and case summaries | D1/D2/D3/D4/D5/D6→P6 relevant account, vent, incident, case, audit, and available message records; P6→D5 selected case-summary viewing audit events. P6 produces the requested staff views, not new inferred clinical findings. |

These are logical responsibilities, not proposed code modules: functions currently share controllers. P6 handles read-only dashboard/case retrieval; P4 handles case/session changes. If minimizing process count is necessary, P4 and P6 can be merged as “Manage and review cases,” conserving every associated boundary and store flow.

### Logical stores (six)

| ID | Store label | Included records |
|---|---|---|
| D1 | Account and profile records | users, students, counselors, admins |
| D2 | Vent records | vent_logs; historic risk labels come from this store |
| D3 | Incident records and evidence | bully_reports and uploaded evidence files |
| D4 | Case and session records | counselor_notes, case_status_history, virtual_meetings |
| D5 | Audit records | audit_logs |
| D6 | Notification activity records | writable/logs/alerts.json; staff OTP attempts in email_logs when available; optional existing sms_logs/email_logs exposed by case summaries |

D6 is a logical grouping of distinct physical stores, not a claim that all channels write the same table. Keep “vent alert attempts” distinct in the data dictionary. No writer for sms_logs was established in the audited controllers; do not invent one. Optional existing message tables are read conditionally by CaseSummaryController and may be omitted from the simplified figure if the caption limits D6 to current vent-alert and OTP records.

### Reverified evidence and necessary omissions

- **No returned emotion labels:** `ml_service/main.py::MLResponse` and `analyze_text()` expose exactly risk_score, risk_level, flag_reason, trajectory, escalation_probability. `VentController::submit()` reads selected moods from POST `emotions`. Label the ML output “Risk and trajectory assessment.”
- **History comes from MySQL:** `VentController.php:61` selects risk_level/created_at from vent_logs for the previous 14 days, excludes deleted rows, and limits history to 100 rows. Do not draw ML↔MySQL directly, old vent text in the history request, FAISS memory, or an ML-owned longitudinal store.
- **Alert file has real reads and writes:** `VentController::logAlert()` writes alerts.json; `isAlertOnCooldown()` reads it; `AdminController::fetchAlertLogs()` reads it for staff views. Logging occurs before some send attempts, so neither “sent” wording in the message nor a stored record proves delivery.
- **OTP logging is narrower:** `AuthController::sendOtpEmail()` conditionally writes email_logs with a sent/failed result. `CaseSummaryController::apiData()` conditionally reads sms_logs and email_logs. Do not describe every incident/vent notification as a database-log entry.
- **Meet routes use the new controller:** `Config/Routes.php` maps session start/cancel/join to VirtualMeetingController. Legacy CaseSummaryController meeting methods must not be treated as the active routed implementation. No Google API response, media stream, or attendance data crosses the PHP boundary.
- **No invented analysis for incidents:** the audited incident controller stores reports and supports staff verification; it does not submit them to the vent-screening API.
- **Role and clinical claims:** preserve applicable route/role restrictions rather than suggesting all staff can administer accounts. Parent wellness notices remain conditional in current local code, including the hide-from-parents branch; no diagram should claim unconditional critical delivery.
- **Encryption wording:** the normal vent path encrypts, but the local exception branch falls back to base64 encoding. A logical DFD should use “Vent records” rather than falsely guaranteeing every stored record is encrypted. This finding does not authorize changing application code during the figure task.

Primary notation/balancing references remain in `dfd-research.md`; this inventory is grounded in the local source and does not verify production deployment.
