# Current paper review

Source: Google Doc `1skrj3zVabmL1ZQbzr4isluGpZL-Qss7bTKeXooRqIVE`, titled **Draft for AI Checking**. Read through its text and retrieved its seven body diagram images on October 2, 2026. The document has one tab, `t.0`.

No Google Docs changes were applied. PDF export returned a file reference, but downloading that export returned HTTP 403; therefore page-level rendered layout was not verified. The diagram image contents were inspected directly.

## First correction: Chapter 3 — Software Architecture

Figure 1 image ID: `kix.eckrzfyppfk`. Its explanation currently occupies indexes 58924–59759; refresh indexes before any write.

The image still labels the vent module as Anonymous Vent, combines Role Management with AES-256 Auth, and names the ML component NLP FAISS Vector. Google Meet is absent. The image fixes Local SMS Gateway and Gmail SMTP as the transports. Its accompanying paragraph calls parents one of three primary user roles and describes conditional anonymous complaints and FAISS vector memory. These are inconsistent with the current source and the paper's preceding updated architecture paragraph.

Changes for the figure:

- Use Student, Counselor, and Super Administrator as the authenticated roles. Parents/guardians are notification recipients.
- Replace Anonymous Vent with Authenticated Safe Space.
- Separate authentication/role control from AES-256-CTR text storage encryption. Passwords use hashing, not AES authentication; staff also have email OTP.
- Replace the FAISS component with FastAPI/PyTorch screening on Hugging Face Spaces. Show current vent text and 14-day risk labels/timestamps going to the service, and risk/emotion results returning to the PHP app.
- Show MySQL records linked to the PHP app. Historical records are sourced from MySQL, not an ML-service vector store.
- Describe SMS as a configured gateway and email as configured SMTP; final transport deployment and delivery are not established by source code.
- Add externally created, staff-published Google Meet links and participant browser redirects. StudentShield does not create the Google meeting automatically or host its video.
- Show notification flow from the application through transports toward recipients. The old diagram's recipient-to-transport arrows are misleading.

Suggested replacement explanation:

Figure 1 presents the architecture of StudentShield. Students, counselors, and super administrators access the CodeIgniter 4 web application through a browser. The application stores records in MySQL and sends current vent text and recent risk history to the FastAPI screening service on Hugging Face Spaces. Screening results support counselor review, while configured SMS and SMTP services handle conditional notifications. Parents and guardians receive eligible alerts without a login portal. For online counseling, authorized staff publish a new Google Meet link for each session, and StudentShield directs the assigned participants to Google Meet.

Also remove only the outdated sentence immediately before the Software Architecture heading that says the older architecture figure is not retained; the old figure is in fact present. Retain the supported factual content of that preceding paragraph and consolidate repetition when the figure is revised.

Code checked: `VirtualMeetingController.php`, `GoogleMeetSessions.php`, `VentController.php`, `ml_service/risk_history.py`, `AuthController.php`, and deployment workflows. These establish checked-in implementation, not independent production delivery or clinical-validation results.

## Later corrections, one at a time

- DFD Level 0: old explanation still claims FAISS; the image omits risk history and meetings and combines staff permissions. Compare its scope with the already updated context diagram.
- DFD Level 1: no risk-history input, staff-verification notification path is missing, provider labels are fixed, and notification persistence is overstated. Update diagram and prose together.
- Use case: the current diagram combines all roles but the caption says Student and parent use cases. Check every permission against role guards before changing relationships.
- List of Figures: entries and numbers differ from actual body captions. Repair after individual figures settle.
- IPO: current image and explanation already include database history and external Google Meet. Do not replace with the legacy FAISS/Jitsi IPO.

Database charts/ERD remain untouched under the user's earlier instruction.
