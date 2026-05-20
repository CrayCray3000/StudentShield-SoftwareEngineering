# DFD Level 1 (Process Decomposition) — Explanation

While the Context Diagram gives the big picture, DFD Level 1 breaks down how StudentShield actually works behind the scenes. It splits the system into five main parts that handle everything from user accounts to emergency notifications.

The process starts with User Account Management, which handles all logins and registrations. It checks user details against the User Data Store and logs every attempt in the Audit Logs to keep the system secure. When a student shares how they feel through the Vent and Mood Processing module, the system sends that text to the HuggingFace ML Service to check for safety risks. Both the mood and the analysis results are saved in the Vents Data Store. If the AI detects a high-risk situation, it flags it immediately for action.

Bullying Report Processing works in a similar way by handling formal complaints. It collects incident details and evidence, then saves them in the Reports Data Store. Just like the venting process, any serious report will trigger a high-priority alert. These insights flow into the Dashboard Management module, which is the main interface for counselors and admins. They use it to view aggregated data from the user, vent, and report databases. Every action a counselor takes is recorded in the Audit Logs to ensure accountability.

Finally, the Notification Dispatcher handles any alerts that are triggered. It turns these alerts into readable messages and sends them through CloudSMS and Gmail. By using both methods, the system ensures that parents or guardians are notified immediately if their child needs help.
