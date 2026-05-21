# Figure 4. DFD Level 1

Although the Context Diagram shows StudentShield as one whole system, DFD Level 1 explains what happens inside it. The diagram breaks the system into five main processes: managing user accounts, analyzing vent submissions, processing bullying reports, monitoring cases and analytics, and dispatching notifications.

User Account Management handles login and account-related requests from the student. Since the database is already normalized, the first data store is shown as User/Profile Records. This represents the related account tables: `users`, `students`, `counselors`, and `admins`. The system checks the submitted credentials through these records and saves important account activity in `audit_logs`.

When a student submits a vent, Analyze Vent Submissions sends the text to the HuggingFace ML Service. The service returns the risk classification, and the system saves the vent result in `vent_logs`. If the vent reaches a serious risk level, the system prepares a verified alert for notification.

Bullying Report Processing handles reports submitted by students. It saves the report details and evidence in `bully_reports`, then sends a verified alert when the case needs attention. Monitor Cases and Analytics collects user records, vent records, and report records so counselors and super admins can review cases from the dashboard. Any verification action from the counselor or super admin goes back into the system and is recorded in `audit_logs`.

Finally, Dispatch Notifications receives verified alerts and turns them into messages for the parent or guardian. SMS messages are sent through the Local SMS Gateway, while email messages are sent through the Gmail SMTP Server. The system also records these outgoing alerts in Notification Logs, which represent `sms_logs` and `email_logs`.
