# Role separation and paper update plan

Prepared: 18 October 2026. Application changes are local; production has not been updated.

## Implemented behavior

StudentShield has four login roles, including three distinct staff roles:

| Role | Code | Responsibility | Restricted information |
| --- | --- | --- | --- |
| Student | 0 | Own portal, submissions and counseling-session requests | Other students' information and staff endpoints |
| Counselor | 1 | Student profiles, cases, classifications, notes, sessions, alerts and registration decisions | System account management, global audit logs and Principal dashboard |
| Super Admin | 2 | Login accounts, roles, passwords, enabling/disabling access, audit/security activity | Student names in the account directory, profiles, guardian contacts, vent text, case details, risk results and counseling records |
| Principal | 3 | Filtered school-level aggregates, trends and summary exports | Individual student records, account management and audit logs |

Principal uses the existing staff login and email OTP workflow, including its existing trusted-device policy. Principal contact information uses `admins`; there is no new `principals` table.

Super Admin sees student account numbers rather than student names or profile fields. Creating a Student login does not approve its registration. Enabling system access also does not approve a pending or rejected registration. Changing roles retains existing profile/history rows instead of deleting student records.

Registration states are `pending`, `approved` and `rejected`. Only Counselor can make decisions, through POST requests protected by authentication and CSRF. A decision only updates a still-pending Student account. Rejection retains the user and student record, without enabling access. Counselor can view pending, approved and rejected registrations.

The shared authorization filter checks the current database role, account access and session version on each protected request. Account changes, password resets and deactivation revoke old sessions/tokens. Controllers also check their required roles before querying or changing records. Automatic controller routing is disabled. Cross-role URLs/APIs receive HTTP 403.

Audit entries include actor account, action, affected reference, timestamp and IP address. Safe Space/incident creation and session request/publication/closure also record metadata-only actions. Student names are masked in the audit actor column. Logs do not copy vent text, notes or guardian contacts. IP means the address observed by the server, or supplied through an explicitly configured trusted proxy; arbitrary forwarded headers are not trusted. Older records without IP display “Not recorded.” IP is not a person's identity or location.

## Principal metric definitions

- Supported periods: Today, Yesterday, Last 7 Days, Last 30 Days, This Week, This Month and custom dates. Custom ranges are inclusive, end no later than today, and span at most 366 days. Calendar boundaries use Philippine time; weeks start Monday.
- All event cards, charts, tables and exports use the same grade, section, event-type, risk and date filters.
- “Students registered in period” counts the registration-date cohort. Active accounts are approved/enabled members of that cohort, not users currently online. Selecting event/risk filters also requires the cohort member to have a matching event.
- Event types reflect existing records: Safe Space and Bullying reports. The application does not invent Academic or Behavioral incident categories.
- Safe Space risk distributions use existing Low, Medium, High and Critical labels. Bullying reports are not assigned a Safe Space risk classification.
- Attention counts distinct students with unreviewed High/Critical Safe Space entries within the selected filters.
- Today/week/month totals are intersected with the selected period. Earlier custom ranges therefore correctly have zero events “today.”
- Each bullying report is counted once. Grade/section uses a linked victim's profile, or the reporter's when no victim account is linked. Missing values are labeled “Not specified.”
- Archived records are excluded. Day trends include dates with zero events. Comparison uses the immediately preceding period of equal length; zero previous events show no percentage baseline.
- Principal SQL returns counts/group labels only. Names, student IDs, message bodies, notes, contacts, evidence and meeting links are not selected for its API. CSV export follows the same filters and escapes spreadsheet formulas in labels.

## Database and deployment

New `users` columns:

| Column | Purpose |
| --- | --- |
| `registration_status VARCHAR(12)` | Retain Counselor approval/rejection decisions separately from system access |
| `access_disabled TINYINT(1)` | Explicitly disable a Student or staff account |
| `auth_version INT` | Revoke existing sessions when account access changes |

`audit_logs.ip_address` and `user_agent` already existed. This update adds consistent capture/display, not a new audit entity. Existing role 0/1/2 identifiers are preserved; Principal is role 3.

Local database: the additive migration was applied and checked twice for idempotence; the account count was preserved. Legacy active students become approved and legacy inactive students become pending. Previously deleted registrations cannot be reconstructed by this migration.

Before production deployment, run `database/2026-10-18-role-separation.sql` **once** in phpMyAdmin against a database that does not yet contain these three columns. Alternatively, use the idempotent CI4 migration where CLI is available. InfinityFree does not require CLI/SSH for the supplied SQL route. Keep the database upgrade ahead of the application deployment. Existing sign-ins will need to authenticate again because they lack the new session version.

Create/assign Principal through Super Admin's Accounts page and provide the staff OTP email. Do not change student case ownership or create fabricated Principal accounts in a deployment script. Configure `Config\App::$proxyIPs` only for known, verified reverse proxies if the hosting path requires original-client IP resolution.

## Paper changes, in order

The local paper reference inspected was `outputs/capstone-compliance/source-working-copy.docx` (4 October working copy). It may precede edits in the live Google Doc. Use the section names and quoted claims below to locate passages in the latest paper; recheck numbering/page references there. This plan does not alter the paper or its page setup.

### 1. Lock the access matrix and scope

Update **Specific Objectives**, **Scope and Delimitations**, **End Users/Beneficiaries**, **Conceptual Framework inputs/outputs**, **Functional Requirements – Role-Based Access Control**, **Account Creation**, **Authentication**, **Registration**, and **Security/Privacy**.

Mandatory corrections found in the working copy:

- Replace “Super Admins can do everything Counselors can…” with system administration only.
- Replace claims that Super Admin enters student IDs, sections, adviser and parent contact data through User Management. Those profiles are not on the new account screen.
- Replace descriptions combining “administrative counselor” responsibilities. State the distinct staff responsibilities in the matrix above.
- Add Principal as a staff user and intended beneficiary of aggregate school reports. Keep the capstone's Junior High scope; a generic Grade 12 example is not evidence that the study expanded its population.
- Describe Counselor-only Pending → Approved/Rejected decisions, retained rejected records, and login/access restrictions. Approval is not the same as consent; do not rename these states “Pending Consent.”
- Add role-aware backend authorization, direct URL/API denial, account/session revocation and audit IP information.
- Replace campus-wide trends attributed solely to the Counselor dashboard with the new Principal reporting responsibility. Counselor still retains its existing operational analytics.

Suggested scope statement for the later writing pass:

> StudentShield separates staff responsibilities through role-based access control. The Counselor manages student cases and registration decisions; the Super Admin maintains system accounts and audit activity; and the Principal reviews filtered school-level summaries without accessing individual counseling records. Student access remains limited to their own portal and services.

### 2. Revise diagrams together

| Artifact | Required change |
| --- | --- |
| System architecture | Show Principal consuming aggregate reporting; preserve the PHP/MySQL and Hugging Face service separation |
| DFD context/Level 0 | Add Principal's filter/report exchanges; remove Super Admin flows receiving case text, risk profiles or clinical notifications |
| DFD Level 1 | Separate account/security management, Counselor case/registration work, and generation of aggregate Principal reports; label actual data stores |
| Super Admin flowchart | Staff authentication → Accounts/Audit Logs → account/security action → audit record → sign out; remove intelligence feed, cases and alert-delivery review |
| Counselor flowchart | Include registration review and approve/reject branches, then existing student case/triage workflows |
| Principal flowchart | Authentication → choose filters → view aggregates/trends → optional filtered export → sign out |
| Use case diagram | Add Principal actor and View School Overview, Filter Analytics, View/Export Summary Report; attach individual case actions only to Counselor and system administration only to Super Admin |
| ERD and data dictionary | Add role code 3 and the three `users` columns; show existing audit IP/agent fields; Principal contacts reuse `admins` rather than an invented new table |

Keep one clean black-and-white ERD with straight connectors, as previously agreed. Shared connector routing must preserve each relationship and its cardinality. Regenerate figure captions, references, lists of figures and TOC only after diagrams and text are final.

The working copy's **Super Admin Flowchart**, **DFD explanations**, **Use Case explanation**, and **Functional Requirements** currently disagree about what Super Admin can access. Revise them as one change so the final paper has one consistent matrix.

### 3. Update interface and implementation evidence

Replace outdated screenshots with the actual Principal overview/filters/export, Super Admin Accounts/Audit Logs with IP, Counselor registration decisions/history, and current student Safe Space/Incident forms. Capture actual application pages at desktop and mobile widths. Use synthetic accounts and records; AI mockups are design references, not implementation evidence.

Update the security/implementation narrative to identify route/filter checks, controller checks and aggregate SQL. Explain metric definitions above, particularly registration-date cohorts, attention criteria, archived records and equivalent-period comparisons.

### 4. Extend the test-case tables

Add expected-result scenarios for:

1. Every role requesting each other role's page/API, including case summaries, evidence, meeting operations, account management, registrations, analytics and exports.
2. Super Admin/Principal requests for individual student IDs returning denial without confidential fields.
3. Pending and rejected Students failing password and remembered-device access; approved Students gaining access.
4. Counselor decisions on a pending Student, repeat/racing decisions, and attempts to approve staff accounts.
5. Account disabling, role changes and password resets invalidating old sessions/tokens; OTP completion after access changes.
6. Date boundaries, all presets, custom ranges, empty filters and combined grade/section/category/risk filters.
7. Cards, charts, accessible tables and exported CSV agreeing under identical filters; zero previous-period denominators.
8. Audit actor/target/action/timestamp/IP capture, legacy missing IPs, escaped output, and spoofed forwarded-header rejection.

Record verified software test results separately from respondent usability/evaluation results. Do not claim new survey responses or revised evaluation scores until those activities are actually conducted.

### 5. Finish consistency checks

Check role names/codes across Chapters I–II, diagrams, data dictionary, test tables, screenshots, appendices/manuals and conclusions. Preserve the existing ML model, encryption and alert-triage descriptions unless a specific verified implementation update warrants correcting them. The recent presentation work and new staff permissions do not constitute a new model evaluation.

Recommended writing sequence: access matrix and definitions → objectives/scope/requirements → diagrams/data dictionary → workflow explanations → screenshots/manual → test evidence → captions and TOC. Keep the original margins, header/footer setup and paper formatting during that later edit.


## Verification completed

- 118 offline PHPUnit tests, 744 assertions pass, including direct route/controller denials, session/OTP revocation, registration retention, SQL aggregates and IP spoofing checks.
- Twenty browser layouts pass across Principal, Accounts, Audit Logs and Registrations at desktop, tablet and phone widths. Filtered API totals and CSV totals agree; forbidden role requests return HTTP 403.
- Staff login and OTP-resend contact projections execute successfully on local MariaDB; regression tests cover their SQL parsing.
- Changed PHP files pass syntax checks and the source diff passes whitespace checks.
- Local MySQL/MariaDB schema columns are verified present and existing account counts are preserved. Browser screenshots use only isolated SQLite fixture data.
- Live production deployment, production proxy-chain configuration, real email OTP delivery and real SMS delivery have not been exercised in this task. No paper pages or formatting were edited and no code has been pushed.
