# StudentShield prototype synchronization audit

Reference date: 1 October 2026. This is an internal implementation map, not a statement that the prototype has passed visual review.

## Evidence and limits

The source is the current working tree, including its uncommitted changes. The local app runs at http://127.0.0.1:8080. MySQL `student_shield` now contains 8 accounts, 5 student profiles, 88 Safe Space entries and 4 incident reports. These are existing local records; no sample records were created for this audit. Earlier in the session the database was empty; that obstacle is resolved.

Figma file: jsz5S5cyXNPcuEYMgl3ATb. Preserve `Design` and all other original pages. The target page is `771:684`, `LIVE WEBSITE SYNC — Oct 1 2026`, with 19 existing screens. Its current screen labels do not prove that its content is accurate. The first inspection found Archivo fonts, substitute Unicode icons, old Meet labels, eight distress-only mood choices, lorem ipsum in reused staff components, and chart interactions inherited from the original components.

The browser connection failed temporarily and recovered after the user restarted Codex. Desktop comparison uses an explicit 1919 × 945 viewport. Measurements must come from the rendered page, including current CSS overrides; older inline Tailwind values alone are insufficient.

## Rendering

CodeIgniter 4 renders PHP views. Tailwind CDN configuration, view-specific CSS, `partials/school_brand.php`, and `public/css/student-forms.css` jointly determine appearance. Body text uses Inter; headings use Outfit. The current student login background and form branding gradient is `#0D7B34` → `#096328` → `#075826`. Shared brand values can be overridden by CSS; read computed styles for each screen. Staff charts use Chart.js and ApexCharts. Import real inline SVGs and the relevant Font Awesome assets, rather than replacing icons with Unicode symbols. The mood emoji are actual source content and should be retained.

## Roles and access

Role 0 is student, 1 guidance counselor, 2 super administrator. Both staff roles use `/admin/dashboard`. The effective AuthFilter blocks counselors from `/admin/users` and `/admin/audit-logs`; those screens belong to super administrators. Pending registrations, case review and meeting setup are available to both staff roles. Staff email OTP is part of authentication unless an existing trusted-device credential applies. Do not bypass it for capture. The current source also enforces a 15-minute idle timeout.

## Student flow

- `/login` → POST `/login/authenticate` → `/student/dashboard` only after valid credentials and active-account checks.
- `/register` → POST `/register/process` creates an inactive student account and redirects to login with the approval message. Password validation and consent are required. Registration does not grant immediate dashboard access.
- Dashboard → `/student/vent` or `/report-bullying` (also `/report/bullying`). The student logo returns to action selection. The form back control follows its implemented navigation history behavior; it should not be treated as another fixed home link.
- Safe Space accepts text and optional, multiple mood choices: Okay, Calm, Happy, Hopeful, Sad, Angry, Anxious, Lonely, Overwhelmed, Hurt, Numb and Scared. The hidden emotions field serializes the selections. These selections do not replace model risk classification.
- The textarea placeholder, breathing helper, privacy checkbox and Meet card must match the current view and stylesheet. The current privacy wording is narrower than the old promise in Figma.
- POST `/student/vent/submit` evaluates ML/local fallback, encrypts stored text and saves a Pending entry. Submission must not be represented by an unconditional prototype success transition.
- Incident reporting supports self and bystander modes. Bystander mode reveals the target student's name field. Evidence is an optional validated image/video up to 10 MB. Draft storage is implemented in browser localStorage for form text and roles/names; it does not preserve an uploaded file.
- Report submission creates `pending_review`. Do not submit a report or vent merely to generate prototype content because notification paths may execute.

## Risk and notification behavior

VentController requests ML analysis with its authenticated API header and runs the local multilingual fallback. HIGH prompts counselor triage; CRITICAL normally prompts counselor and parent notification attempts, subject to contact availability/cooldowns. The current code allows `hide_from_parents` to suppress parent alerts even for CRITICAL entries. This contradicts the unconditional CRITICAL rule in AGENTS.md and was previously explicitly left unchanged by the user's UI-only choice. Do not invent an emergency exception in the prototype or claim it is enforced.

Notification attempts are not proof of delivery. Incident submission can send a reporter confirmation through a contact query that may select the parent's email. Verification attempts notifications to linked involved parties' parents. A second false-report strike can also notify a parent. Therefore, do not claim that all parent contact occurs only on incident verification.

## Staff review

Routes and handlers are in Config/Routes.php, AdminController.php, UserController.php and CaseSummaryController.php. Safe Space resolve marks the entry Reviewed; archive/restore are separate operations. Incident states include `pending_review`, `verified`, `rejected_fake` and `resolved`. Verify and reject perform real backend updates; prototype navigation must not simulate their completion without validation. False-report strikes warn first, can notify a parent on the second strike, and lock reporting after the threshold. Registration approval activates an account and logs the action. Rejection removes the registration/account; do not invoke it during UI inspection.

Case summary aggregates nondeleted Safe Space entries, involvement-linked incident reports, recorded notification logs, counselor notes and status history. The categorical chart mapping is 25/50/75/100 for LOW/MEDIUM/HIGH/CRITICAL; do not label that mapping as the stored model score. Current risk, highest historical risk and entry-specific sentiment/risk percentages are distinct.

Valid case statuses: `active_monitoring`, `intervention_ongoing`, `parent_consultation_required`, `escalated`, `under_investigation`, `resolved`, `closed`. The handler accepts valid selected statuses; it does not impose a fabricated linear progression. Notes use observation, recommendation, intervention, follow_up or general types. Audit logging records supported controller actions; do not infer audit coverage from button presence.

## Google Meet

Actual routes bind VirtualMeetingController and Libraries/GoogleMeetSessions, not the older unused meeting methods in CaseSummaryController.

1. Student POST `/student/meeting/request` creates a requested session. Waiting means a pending request, not a meeting already being hosted.
2. Student may POST `/student/meeting/cancel-request`; staff sees pending requests through the implemented dashboard/case APIs.
3. Staff creates a new Google Meet externally, enters the link, and confirms host/admission settings. StudentShield does not create Google Meet spaces through OAuth.
4. Publishing requires a valid `https://meet.google.com/abc-defg-hij` format, host confirmation, an active student and a link that has not been used before. It publishes one session for the selected student.
5. The student status endpoint exposes a StudentShield join route only for an active, valid, unexpired meeting. Access lasts two hours. Join checks the assigned student or assigned host; another staff account does not gain access merely by being super administrator.
6. Closing StudentShield access changes its session status; it does not terminate the Google Meet call. The host must separately end the call for everyone in Google Meet.

Current student card labels: Counselor Video Session; Private 1-on-1 consultation; Request Session; Waiting for counselor; Cancel session request; Join Google Meet. Keep request/publish/end buttons as real-state controls, not fake navigation shortcuts.

## Capture and prototype checks still required

Recheck all 19 target frames against current rendered pages and actual records. Fix the student layouts, fonts, SVGs, current colors and wording. Replace lorem ipsum and duplicated or mismatched staff records with the captured local data. Match chart data/configuration and viewport scroll positions. Remove inherited component interactions that do not exist in the app. Wire only verified route navigation, history back, tabs and modal opening/closing. Login, approval, submission, verification, rejection, status save and meeting publication require server validation and must not be wired as unconditional successes. Validate editable hierarchy, text bounds, image assets and screenshots before declaring completion.
