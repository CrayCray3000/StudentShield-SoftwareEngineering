"""
Generate StudentShield Defense Reviewer as a formatted .docx Word document.
"""
from docx import Document
from docx.shared import Pt, Inches, Cm, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml.ns import qn
import os

doc = Document()

# ── Global style defaults ──
style = doc.styles['Normal']
font = style.font
font.name = 'Garamond'
font.size = Pt(12)
font.color.rgb = RGBColor(0x1A, 0x1A, 0x1A)
style.paragraph_format.space_after = Pt(4)
style.paragraph_format.line_spacing = 1.15

for level in range(1, 4):
    hs = doc.styles[f'Heading {level}']
    hs.font.name = 'Garamond'
    hs.font.color.rgb = RGBColor(0x0B, 0x2E, 0x5E)
    hs.font.bold = True
    if level == 1:
        hs.font.size = Pt(18)
        hs.paragraph_format.space_before = Pt(24)
    elif level == 2:
        hs.font.size = Pt(14)
        hs.paragraph_format.space_before = Pt(18)
    else:
        hs.font.size = Pt(12)
        hs.paragraph_format.space_before = Pt(12)

# ── Helper functions ──
def add_bold_paragraph(text, size=12, color=RGBColor(0,0,0), align=WD_ALIGN_PARAGRAPH.LEFT):
    p = doc.add_paragraph()
    p.alignment = align
    r = p.add_run(text)
    r.bold = True
    r.font.size = Pt(size)
    r.font.name = 'Garamond'
    r.font.color.rgb = color
    return p

def add_paragraph(text, bold=False, italic=False, size=12):
    p = doc.add_paragraph()
    r = p.add_run(text)
    r.bold = bold
    r.italic = italic
    r.font.size = Pt(size)
    r.font.name = 'Garamond'
    return p

def add_bullet(text, level=0, bold_prefix=None):
    p = doc.add_paragraph(style='List Bullet')
    p.paragraph_format.left_indent = Inches(0.5 + level * 0.35)
    if bold_prefix:
        rb = p.add_run(bold_prefix)
        rb.bold = True
        rb.font.name = 'Garamond'
        rb.font.size = Pt(11)
        rn = p.add_run(text)
        rn.font.name = 'Garamond'
        rn.font.size = Pt(11)
    else:
        p.clear()
        r = p.add_run(text)
        r.font.name = 'Garamond'
        r.font.size = Pt(11)
    return p

def add_numbered(text, number, bold_prefix=None):
    p = doc.add_paragraph()
    p.paragraph_format.left_indent = Inches(0.5)
    if bold_prefix:
        rb = p.add_run(f"{number}. {bold_prefix}")
        rb.bold = True
        rb.font.name = 'Garamond'
        rb.font.size = Pt(11)
        rn = p.add_run(text)
        rn.font.name = 'Garamond'
        rn.font.size = Pt(11)
    else:
        r = p.add_run(f"{number}. {text}")
        r.font.name = 'Garamond'
        r.font.size = Pt(11)
    return p

def add_table(headers, rows):
    table = doc.add_table(rows=1 + len(rows), cols=len(headers))
    table.style = 'Table Grid'
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    # Header row
    for i, h in enumerate(headers):
        cell = table.rows[0].cells[i]
        cell.text = ''
        r = cell.paragraphs[0].add_run(h)
        r.bold = True
        r.font.name = 'Garamond'
        r.font.size = Pt(10)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)
        shading = cell._element.get_or_add_tcPr()
        shade_elem = shading.makeelement(qn('w:shd'), {
            qn('w:val'): 'clear',
            qn('w:color'): 'auto',
            qn('w:fill'): '0B2E5E'
        })
        shading.append(shade_elem)
    # Data rows
    for ri, row in enumerate(rows):
        for ci, val in enumerate(row):
            cell = table.rows[ri + 1].cells[ci]
            cell.text = ''
            r = cell.paragraphs[0].add_run(str(val))
            r.font.name = 'Garamond'
            r.font.size = Pt(10)
    doc.add_paragraph()  # spacer
    return table

def add_qa_block(question, wrong_answer, safe_answer):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(10)
    rq = p.add_run("PANEL QUESTION: ")
    rq.bold = True
    rq.font.name = 'Garamond'
    rq.font.size = Pt(11)
    rq.font.color.rgb = RGBColor(0xB0, 0x00, 0x00)
    rq2 = p.add_run(f'"{question}"')
    rq2.italic = True
    rq2.font.name = 'Garamond'
    rq2.font.size = Pt(11)

    if wrong_answer:
        pw = doc.add_paragraph()
        pw.paragraph_format.left_indent = Inches(0.3)
        rw = pw.add_run("✘ DO NOT SAY: ")
        rw.bold = True
        rw.font.name = 'Garamond'
        rw.font.size = Pt(11)
        rw.font.color.rgb = RGBColor(0xCC, 0x00, 0x00)
        rw2 = pw.add_run(f'"{wrong_answer}"')
        rw2.font.name = 'Garamond'
        rw2.font.size = Pt(11)

    ps = doc.add_paragraph()
    ps.paragraph_format.left_indent = Inches(0.3)
    rs = ps.add_run("✔ SAFE ACADEMIC ANSWER: ")
    rs.bold = True
    rs.font.name = 'Garamond'
    rs.font.size = Pt(11)
    rs.font.color.rgb = RGBColor(0x00, 0x6B, 0x3F)

    pa = doc.add_paragraph()
    pa.paragraph_format.left_indent = Inches(0.5)
    ra = pa.add_run(safe_answer)
    ra.font.name = 'Garamond'
    ra.font.size = Pt(11)

def add_separator():
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r = p.add_run("─" * 70)
    r.font.size = Pt(8)
    r.font.color.rgb = RGBColor(0xAA, 0xAA, 0xAA)


# ══════════════════════════════════════════════════════════════════════
#                         COVER PAGE
# ══════════════════════════════════════════════════════════════════════
for _ in range(4):
    doc.add_paragraph()

add_bold_paragraph("STUDENTSHIELD", size=26, color=RGBColor(0x0B, 0x2E, 0x5E), align=WD_ALIGN_PARAGRAPH.CENTER)
add_bold_paragraph("CAPSTONE DEFENSE REVIEWER & STUDY GUIDE", size=16, color=RGBColor(0x0B, 0x2E, 0x5E), align=WD_ALIGN_PARAGRAPH.CENTER)

doc.add_paragraph()

p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
r = p.add_run("Machine Learning-Based Emotional Distress Detection and Bullying Reporting\nSystem with Automated SMS and Email Alerts\nfor Saint Vincent Blessed School of Manila")
r.italic = True
r.font.size = Pt(12)
r.font.name = 'Garamond'
r.font.color.rgb = RGBColor(0x44, 0x44, 0x44)

for _ in range(2):
    doc.add_paragraph()

info_lines = [
    ("Proponents:", "Enriquez, J.A. · Melgarejo, J.C. · Meneses, V.C.M. · Ramos, J.C. · Urayanza, A.P."),
    ("Adviser:", "Mr. Edward James V. Grageda"),
    ("Degree:", "Bachelor of Science in Information Technology"),
    ("Institution:", "National Teachers College – SAST, Quiapo, Manila"),
    ("Client School:", "Saint Vincent Blessed School of Manila (SVBSM)"),
    ("Defense Date:", "May 2026"),
]
for label, value in info_lines:
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    rl = p.add_run(f"{label} ")
    rl.bold = True
    rl.font.size = Pt(11)
    rl.font.name = 'Garamond'
    rv = p.add_run(value)
    rv.font.size = Pt(11)
    rv.font.name = 'Garamond'

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#                    TABLE OF CONTENTS
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('TABLE OF CONTENTS', level=1)

toc_items = [
    "1.  Project Purpose & Statement of the Problem",
    "2.  General & Specific Objectives of the Study",
    "3.  Significance of the Study & Target Beneficiaries",
    "4.  The Technical Architecture & Decoupled Stack",
    "5.  Deep Dive: The Neural Machine Learning Pipeline",
    "6.  Two-Tiered Safety Net & Local Heuristic Fallback",
    "7.  User Journeys & System Workflows",
    "8.  Methodology, SDLC, Sampling & Statistical Treatment",
    "9.  System Test Plan (Scenarios by Module)",
    "10. Panel Questions & Answers (Defense Traps & Safe Passes)",
    "11. Review of Related Literature – Quick Reference Cheat Sheet",
    "12. Key Definition of Terms",
    "13. Laws & Legal Compliance Summary",
    "14. Practical Oral Defense Tips for the Team",
]
for item in toc_items:
    p = doc.add_paragraph()
    r = p.add_run(item)
    r.font.name = 'Garamond'
    r.font.size = Pt(11)

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#              SECTION 1: PROJECT PURPOSE
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('1. Project Purpose & Statement of the Problem', level=1)

doc.add_heading('The Core Problem', level=2)
add_paragraph(
    "Junior High School students at SVBSM face modern digital threats—cyberbullying, online harassment—and hidden "
    "mental distress. The school's traditional approach is reactive and decentralized: it relies on manual, paper-based "
    "reporting, decentralized report management, and verbal reports. This creates massive delays, paper trails, and a "
    "failure to intervene during the \"Golden Hour\"—the critical window immediately following a student's expression "
    "of distress when support is most effective. Parents are often the last to know, and guidance staff are overwhelmed with paperwork."
)

doc.add_heading('StudentShield\'s Core Purpose', level=2)
add_paragraph(
    "To provide SVBSM with a dependable, automated, and centralized platform for real-time risk detection and "
    "proactive student intervention. By leveraging Natural Language Processing (NLP) and automated dual-dispatch "
    "alerting (SMS + Email), the system bridges the school-to-home communication gap, flags high-stress vulnerabilities "
    "instantly, and streamlines counseling workflows."
)

add_bold_paragraph("Four Design Pillars:", size=12)
add_bullet("Proactive Risk Detection – Switch from reactive disciplinary response to early NLP-based detection.", 0)
add_bullet("Prioritized Intervention – Classify incidents into Low, Medium, High, and Critical tiers.", 0)
add_bullet("Trend Analytics & Monitoring – Use 14-day behavioral trajectory tracking (FAISS) to identify worsening patterns.", 0)
add_bullet("Data-Driven Decision Making – Empower administration with evidence-based dashboards and trend reports.", 0)

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#              SECTION 2: OBJECTIVES
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('2. General & Specific Objectives of the Study', level=1)

doc.add_heading('General Objective', level=2)
add_paragraph(
    "To design and develop an NLP-powered monitoring, reporting, and intervention system (StudentShield) for SVBSM "
    "Junior High School students that automates bullying detection, categorizes emotional distress risks, and facilitates "
    "proactive, data-driven administrative interventions."
)

doc.add_heading('Specific Objectives', level=2)
objectives = [
    ('Secure "Safe Space" Web Portal: ', 'Provide JHS students a secure web page to conditionally anonymously submit emotional vents and formal bullying incident reports (with photo/video evidence uploads).'),
    ('Multi-Model AI Risk Classification Engine: ', 'Implement mDeBERTa-v3 and DistilRoBERTa to analyze vent texts and classify emotional distress into four tiers: Low, Medium, High, and Critical.'),
    ('Behavioral Trajectory Tracking: ', 'Integrate a vector-based search algorithm (FAISS) to analyze a student\'s 14-day history and identify worsening trajectories of cumulative distress.'),
    ('Real-Time Alerting System: ', 'Build automated parent and counselor notification gateways via SMS (Local Android SMS Gateway / Semaphore API) and Email (secure Gmail SMTP).'),
    ('Security & Legal Compliance: ', 'Protect minor student disclosures using AES-256 data-at-rest encryption and bcrypt password hashing. Validate accuracy through pilot testing, in compliance with RA 10173 (Data Privacy Act of 2012).'),
]
for i, (prefix, body) in enumerate(objectives, 1):
    add_numbered(body, i, prefix)

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#              SECTION 3: SIGNIFICANCE
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('3. Significance of the Study & Target Beneficiaries', level=1)

beneficiaries = [
    ("Client School (SVBSM):", "Replaces vulnerable paper record storage with a secure, centralized database compliant with DepEd rules. Streamlines guidance counselor workload."),
    ("Students:", "Provides an inviting, non-threatening digital safe space for venting or reporting incidents anonymously, removing fear of peer retaliation or immediate judgment."),
    ("Guidance Counselors:", "Acts as a virtual assistant filtering hundreds of vents, prioritizing \"High\" and \"Critical\" cases so they can focus clinical support where it is urgently needed."),
    ("Parents/Guardians:", "Fills the communication gap. Parents are immediately alerted via automated SMS and email during life-threatening crises, enabling home intervention alongside school protocols."),
    ("The Proponents:", "Deepened practical experience in combining PHP/CodeIgniter 4 backend with a Python/FastAPI ML microservice, secure API design, and AES-256 cryptographic protocols."),
    ("Future Researchers:", "Provides a case study for integrating transformer models into localized school systems. Foundation for expanding NLP to more Philippine dialects or wearable technology."),
]
for label, desc in beneficiaries:
    add_bullet(desc, 0, label + " ")

add_paragraph("")
add_bold_paragraph("Legal Alignment:", size=12)
add_bullet("Republic Act No. 10627 – Anti-Bullying Act of 2013 (and 2022 revised IRR covering cyberbullying)")
add_bullet("Republic Act No. 10173 – Data Privacy Act of 2012")
add_bullet("Republic Act No. 11036 – Philippine Mental Health Act")
add_bullet("UNICEF 2021 Policy Guidance on AI for Children (human-in-the-loop principle)")

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#              SECTION 4: TECHNICAL ARCHITECTURE
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('4. The Technical Architecture & Decoupled Stack', level=1)

add_paragraph(
    "StudentShield uses a decoupled, hybrid architecture to keep the client's operational cost low while maintaining "
    "secure, high-performance ML processing. The system is split into separate layers so that heavy AI computations "
    "never slow down the student-facing portal."
)

doc.add_heading('Technology Stack Summary', level=2)
add_table(
    ["Layer", "Technology", "Role"],
    [
        ["Web Backend", "PHP 8.1+ / CodeIgniter 4.4", "Routing, sessions, CRUD, encryption, audit logs"],
        ["Database", "MySQL 8.0 (InnoDB)", "Relational storage for users, vents, reports, alerts"],
        ["Frontend", "Tailwind CSS 3.0 / Vanilla JS", "Responsive dashboards, student portal UI"],
        ["ML Microservice", "Python 3.10+ / FastAPI 0.109", "Parallel transformer inference, risk scoring"],
        ["Containerization", "Docker", "Stable, reproducible ML environment on Azure VM"],
        ["Vector Engine", "FAISS (Facebook AI)", "14-day behavioral trajectory similarity search"],
        ["AI Frameworks", "PyTorch / HuggingFace Transformers", "Model loading, tokenization, inference"],
        ["Email Alerts", "Gmail SMTP + App Passwords", "HTML email notifications to parents/counselors"],
        ["SMS Alerts", "Local Android SMS Gateway + Semaphore API", "Plain-text SMS to parents during critical risk"],
        ["Encryption", "AES-256-GCM / bcrypt", "Data-at-rest encryption and password hashing"],
    ]
)

doc.add_heading('Why Decoupled (Microservice Architecture)?', level=2)
add_bullet("If the ML service crashes, the student portal stays online — vents are still saved and the local heuristic fallback catches crises.")
add_bullet("The ML service can be upgraded, scaled, or moved to a faster server independently without touching the PHP codebase.")
add_bullet("Separating concerns ensures the student's lightweight web experience is never delayed by heavy neural inference.")

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#              SECTION 5: ML PIPELINE DEEP DIVE
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('5. Deep Dive: The Neural Machine Learning Pipeline', level=1)

add_paragraph(
    "Every time a student submits a vent, the PHP backend sends the raw text to the FastAPI microservice via a "
    "secure HTTP POST request. The microservice runs a parallel inference pipeline consisting of four stages:"
)

doc.add_heading('Stage 1: Primary Distress Classification (mDeBERTa-v3)', level=2)
add_bullet("Model: Fine-tuned multilingual DeBERTa-v3 (ashlogg/student-shield-mdeberta)")
add_bullet("Purpose: Detects psychological distress markers, including Taglish code-switching and Gen-Z slang.")
add_bullet("Why mDeBERTa: He et al. (2021) demonstrated it outperforms BERT and RoBERTa on all SuperGLUE benchmarks while requiring fewer computational resources — critical for CPU-only school servers.")
add_bullet("Fail-Safes: If the primary model fails, routes to SamLowe/roberta-base-go_emotions and unitary/toxic-bert.")

doc.add_heading('Stage 2: Granular Emotion Extraction (DistilRoBERTa)', level=2)
add_bullet("Model: j-hartmann/emotion-english-distilroberta-base")
add_bullet("Output: Probability scores for 7 emotions — sadness, fear, anger, disgust, joy, surprise, neutral.")
add_bullet("Purpose: Differentiates active anger (bullying indicator) from deep sadness (self-harm indicator) to weight the final risk score correctly.")

doc.add_heading('Stage 3: Semantic Crisis Matching (MiniLM)', level=2)
add_bullet("Model: paraphrase-multilingual-MiniLM-L12-v2")
add_bullet("Method: Converts text into 384-dimensional vector embeddings and computes cosine similarity against a pre-built \"Crisis Anchor Set.\"")
add_bullet("Crisis Anchors cover English, Tagalog, Ilocano, and Bisaya phrases (e.g., \"Gusto nako mamatay,\" \"Ayoko na talaga\").")
add_bullet("Purpose: Catches creative or implicit suicidal ideation that keyword filters would miss entirely.")

doc.add_heading('Stage 4: Contextual Behavioral Tracking (FAISS)', level=2)
add_bullet("Engine: Facebook AI Similarity Search (FAISS)")
add_bullet("Method: Each vent is converted to a mathematical vector and compared to the student's entries from the last 14 days.")
add_bullet("Metric: \"Trend Velocity\" — the slope of risk escalation. If vectors consistently drift toward negative territory, the risk score is dynamically boosted.")
add_bullet("Purpose: Catches cumulative psychological decay that single-post analysis misses entirely.")

doc.add_heading('Risk Classification Tiers', level=2)
add_table(
    ["Tier", "Score Range", "Description", "System Action"],
    [
        ["LOW", "1% – 30%", "Normal emotional release, positive venting", "Logged only. No alert."],
        ["MEDIUM", "31% – 60%", "Moderate frustration, light verbal insults", "Flagged for counselor dashboard review."],
        ["HIGH", "61% – 85%", "Severe distress, physical violence reports", "Urgent priority in counselor dashboard."],
        ["CRITICAL", "86% – 100%", "Direct suicidal intent, imminent self-harm", "Automated SMS + Email to Parents & Counselor Alert."],
    ]
)

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#              SECTION 6: TWO-TIERED SAFETY NET
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('6. Two-Tiered Safety Net & Local Heuristic Fallback', level=1)

add_paragraph(
    "What happens if the machine learning service goes down due to internet outages or server memory limits? "
    "StudentShield implements a safety-first architecture — a \"safety net for the safety net.\""
)

add_table(
    ["Tier", "Location", "Mechanism", "When It Fires"],
    [
        ["Tier 1 – Deep Semantic", "Python FastAPI Service", "mDeBERTa + DistilRoBERTa + MiniLM + FAISS", "Primary analysis (always attempted first)"],
        ["Tier 2 – Local Heuristic", "PHP CodeIgniter App", "Regex keyword scanner against crisis dictionary", "Fires independently AND as fallback if Tier 1 fails"],
    ]
)

add_bold_paragraph("Decision Matrix:", size=12)
add_bullet("If both tiers are operational: The system compares both risk scores and applies the HIGHER of the two (safety-first override).")
add_bullet("If Tier 1 (FastAPI) is offline: The PHP layer silently activates the heuristic scanner as a complete fallback. Critical self-harm keywords (e.g., \"suicide,\" \"laslas,\" \"bigti,\" \"mamatay\") will STILL trigger parent SMS alerts and counselor notifications.")
add_bullet("Result: Zero gaps in crisis detection coverage, regardless of infrastructure state.")

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#              SECTION 7: USER JOURNEYS
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('7. User Journeys & System Workflows', level=1)

doc.add_heading('The Student Journey', level=2)
add_numbered("Secure access via Student ID and private passcode.", 1, "Login: ")
add_numbered("Submit text disclosures in conditional anonymity through the \"Safe Space\" portal.", 2, "Venting: ")
add_numbered("For active, immediate danger — a double-tap on a hidden panic UI instantly triggers a Code Red Parent and Counselor SMS alert with their identity.", 3, "Silent Panic Button: ")
add_numbered("After submitting a highly distressed vent, the interface transitions into a guided 60-second breathing animation to calm the student during their \"Golden Hour.\"", 4, "Post-Vent Grounding UI: ")
add_numbered("A \"Hide from Parents\" toggle allows students to suppress parent SMS alerts if they fear domestic consequences. The counselor is still notified.", 5, "Privacy Toggle: ")

doc.add_heading('The Counselor Journey', level=2)
add_numbered("Visual hub displaying school-wide emotional metrics, risk distribution pie charts, and trend area charts.", 1, "Dashboard Overview: ")
add_numbered("Reads decrypted vents directly in the browser session. The ML engine outputs automated summaries for long text entries.", 2, "AI-Assisted Triage: ")
add_numbered("Counselor clicks \"Verify & Alert Parents\" (Stage 2) to send confirmed bullying alerts to both victim's and bully's parents. No automated accusation without verification.", 3, "Bullying Report Management: ")
add_numbered("24 hours after a Critical alert, the system automatically sends a follow-up verification link to parents (\"Has the issue been settled at home?\") to close the case log.", 4, "Parent Resolution Loop: ")
add_numbered("NLP extracts location names from vent text to generate a visual Heatmap of bullying hotspots, helping the Principal optimize guard patrols.", 5, "Hotspot Analysis: ")
add_numbered("All actions — viewing, resolving, archiving, deleting — are permanently logged with IP address, User Agent, and timestamp.", 6, "Audit Trail: ")

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#              SECTION 8: METHODOLOGY
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('8. Methodology, SDLC, Sampling & Statistical Treatment', level=1)

doc.add_heading('Research Design', level=2)
add_paragraph("Mixed-methods design evaluating both technical ML performance (quantitative accuracy metrics) and user acceptance/usability (qualitative and Likert-scale survey data).")

doc.add_heading('Software Development Life Cycle (SDLC): Agile Scrum', level=2)
add_paragraph(
    "Machine learning systems demand heavy iteration — dataset adjustments, threshold tuning, sarcasm fallback corrections. "
    "Agile's iterative sprints allowed the team to adapt algorithms based on evaluation results before final deployment."
)
add_table(
    ["Sprint", "Focus", "Deliverables"],
    [
        ["Sprint 1", "Foundation & UI", "CodeIgniter 4 web app, Tailwind CSS dashboards, encrypted MySQL schema"],
        ["Sprint 2", "AI Integration", "FastAPI microservice, mDeBERTa & DistilRoBERTa integration, secure API keys"],
        ["Sprint 3", "Testing & Refinement", "FAISS vector tuning, sarcasm logic calibration, Taglish stress tests"],
        ["Sprint 4", "Deployment & Defense", "Azure VM deployment, Docker containerization, final documentation"],
    ]
)

doc.add_heading('Sampling Technique', level=2)
add_bullet("Stratified Random Sampling: Selects student respondents across JHS grade levels/sections. Sample size determined via Slovin's Formula for statistical significance.", 0, "Students: ")
add_bullet("Purposive Sampling: Selects guidance counselors and IT administrators based on their professional expertise and direct involvement with the system.", 0, "Experts: ")

doc.add_heading('Data Gathering Tool', level=2)
add_paragraph("Standardized software evaluation questionnaire adapted from ISO/IEC 25010 guidelines (functional suitability, usability, performance efficiency, and security).")

doc.add_heading('Statistical Treatment', level=2)
add_table(
    ["Method", "Formula / Tool", "Purpose"],
    [
        ["Percentage & Frequency Distribution", "P = (f / N) × 100", "Profile demographics and proportion of survey responses"],
        ["Weighted Mean", "WM = (Σfx) / N", "Central tendency of 5-point Likert scale evaluations"],
        ["Confusion Matrix", "Precision, Recall, F1-Score, Accuracy", "Evaluate ML model's classification correctness on test data"],
    ]
)

doc.add_heading('Slovin\'s Formula (for defense reference)', level=3)
add_paragraph("n = N / (1 + N × e²)", bold=True)
add_paragraph("Where: n = sample size, N = total population, e = margin of error (typically 0.05 for 95% confidence level).")

doc.add_heading('Weighted Mean Interpretation Scale', level=3)
add_table(
    ["Range", "Verbal Interpretation"],
    [
        ["4.21 – 5.00", "Strongly Agree / Highly Acceptable"],
        ["3.41 – 4.20", "Agree / Acceptable"],
        ["2.61 – 3.40", "Neutral / Moderately Acceptable"],
        ["1.81 – 2.60", "Disagree / Slightly Acceptable"],
        ["1.00 – 1.80", "Strongly Disagree / Not Acceptable"],
    ]
)

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#              SECTION 9: SYSTEM TEST PLAN
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('9. System Test Plan (Scenarios by Module)', level=1)

doc.add_heading('Authentication Module', level=2)
auth_tests = [
    ["TC-AUTH-01", "Student login with valid ID and passcode", "Access granted"],
    ["TC-AUTH-02", "Student login with invalid credentials", "Access denied with error message"],
    ["TC-AUTH-03", "Counselor login with 2FA OTP email delivery", "OTP received and accepted"],
    ["TC-AUTH-04", "Super Admin login with OTP", "OTP received and accepted"],
    ["TC-AUTH-05", "Expired/incorrect OTP rejected", "System denies access"],
    ["TC-AUTH-06", "Trusted device cookie bypasses OTP on next login", "OTP step skipped"],
    ["TC-AUTH-07", "Legacy plaintext passwords auto-upgrade to bcrypt", "Hash updated silently on first login"],
]
add_table(["Test Case ID", "Scenario", "Expected Result"], auth_tests)

doc.add_heading('Vent Log Module', level=2)
vent_tests = [
    ["TC-VENT-01", "Student submits a vent entry successfully", "Vent saved, AI analysis triggered"],
    ["TC-VENT-02", "Vent text encrypted with AES-256 before DB storage", "Raw text unreadable in database"],
    ["TC-VENT-03", "FastAPI returns valid risk level JSON", "Dashboard displays correct risk tier"],
    ["TC-VENT-04", "Local keyword fallback when FastAPI is offline", "Crisis keywords still trigger alerts"],
    ["TC-VENT-05", "Sarcasm detector downgrades hyperbolic expressions", "\"kms after math exam\" → Medium/High, not Critical"],
    ["TC-VENT-06", "SMS sent to parent on Critical risk", "Parent receives plain-text wellness check SMS"],
    ["TC-VENT-07", "Email sent to parent on Critical risk", "Parent receives HTML alert email"],
    ["TC-VENT-08", "\"Hide from Parents\" toggle suppresses parent alert", "Counselor notified instead of parents"],
    ["TC-VENT-09", "Rate limiter blocks second submission within 30 sec", "User shown cooldown message"],
]
add_table(["Test Case ID", "Scenario", "Expected Result"], vent_tests)

doc.add_heading('Bullying Report Module', level=2)
bully_tests = [
    ["TC-BULLY-01", "Student submits self-report", "Report saved with reporter as victim"],
    ["TC-BULLY-02", "Student submits report on behalf of another", "Report saved with separate victim field"],
    ["TC-BULLY-03", "Incident details encrypted before saving", "Ciphertext stored in database"],
    ["TC-BULLY-04", "Stage 1: Counselor receives SMS after submission", "Counselor alerted immediately"],
    ["TC-BULLY-05", "Stage 2: \"Verify & Alert Parents\" alerts both sides", "Both victim's and bully's parents receive alerts"],
    ["TC-BULLY-06", "\"Reject as Fake\" blocks all parent alerts", "No alerts sent; report flagged as rejected"],
    ["TC-BULLY-07", "\"Undo\" resets report to pending review", "Status reverted successfully"],
    ["TC-BULLY-08", "Rate limiter blocks report within 120 seconds", "User shown cooldown message"],
]
add_table(["Test Case ID", "Scenario", "Expected Result"], bully_tests)

doc.add_heading('Dashboard & Case Management Module', level=2)
dash_tests = [
    ["TC-DASH-01", "Counselor views decrypted vent text", "Text visible only in active session"],
    ["TC-DASH-02", "Risk pie chart shows correct distribution", "Counts match database records"],
    ["TC-DASH-03", "Trend area chart updates via AJAX", "Chart refreshes without page reload"],
    ["TC-DASH-04", "Counselor marks vent as Resolved", "Status updated, logged in audit trail"],
    ["TC-DASH-05", "Counselor archives (soft-deletes) a vent", "Entry hidden from active view"],
    ["TC-DASH-06", "Counselor restores archived vent", "Entry returns to active dashboard"],
    ["TC-DASH-07", "Bulk delete and bulk restore operations", "Multiple entries processed correctly"],
    ["TC-DASH-08", "Click student name to view 14-day risk timeline", "Profile and trajectory graph displayed"],
]
add_table(["Test Case ID", "Scenario", "Expected Result"], dash_tests)

doc.add_heading('User Management & Audit Log Module', level=2)
admin_tests = [
    ["TC-ADMIN-01", "Super admin creates new student account", "Account created with all required fields"],
    ["TC-ADMIN-02", "Super admin creates counselor/admin account", "Account created with correct role"],
    ["TC-ADMIN-03", "Super admin edits existing user information", "Changes saved and reflected"],
    ["TC-ADMIN-04", "Super admin deletes a user account", "Account removed from active records"],
    ["TC-ADMIN-05", "Counselor blocked from User Management page", "403 Forbidden / redirect"],
    ["TC-ADMIN-06", "Super admin cannot demote/delete own account", "Operation blocked with warning"],
    ["TC-AUDIT-01", "Audit log records every counselor action", "Resolve, archive, verify actions logged"],
    ["TC-AUDIT-02", "Log entry includes IP, User Agent, timestamp", "Complete metadata captured"],
    ["TC-AUDIT-03", "Admin views full audit trail from dashboard", "Searchable log history displayed"],
    ["TC-AUDIT-04", "SMS/Email alert history viewable", "Sent alert records accessible"],
]
add_table(["Test Case ID", "Scenario", "Expected Result"], admin_tests)

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#              SECTION 10: PANEL QUESTIONS & ANSWERS
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('10. Panel Questions & Answers (Defense Traps & Safe Passes)', level=1)

add_paragraph("Below are the most likely questions panelists will ask, common wrong answers to avoid, and academically safe responses grounded in your paper's literature and methodology.", italic=True)

add_separator()

add_qa_block(
    "Why did you build custom models instead of just calling the OpenAI (ChatGPT) API? It's much simpler.",
    "Because ChatGPT is hard to code.",
    "Using proprietary cloud models like OpenAI presents three critical showstoppers: "
    "(1) Compliance & Security — RA 10173 (Data Privacy Act) prohibits sending sensitive emotional disclosures of minor students to foreign third-party servers. Our architecture guarantees all data is encrypted locally and processed in our private Azure VM. "
    "(2) Contextual Nuances — Generic cloud models are not fine-tuned for local Taglish code-switching or Gen-Z school slang. Our mDeBERTa model is specifically tuned for this linguistic domain. "
    "(3) Financial Sustainability — API token costs scale rapidly. Our local CPU-friendly microservice runs on a fixed-cost VM with zero per-token fees, which is critical for a school budget."
)

add_separator()

add_qa_block(
    "Is your system diagnosing mental illness? What if the AI misclassifies a serious suicidal case?",
    "Our AI is 100% accurate and can replace clinical therapists.",
    "Absolutely not. StudentShield is strictly a screening and triaging framework, not a clinical diagnostic tool. "
    "The system operates on a 'Human-in-the-Loop' model in strict compliance with UNICEF 2021 AI guidelines for children. "
    "The AI acts as a virtual first-responder that prioritizes critical cases for human intervention, but all official decisions "
    "and student clinical care are handled by trained guidance professionals. To prevent missed cases, we also developed a Local "
    "Heuristic Fallback using direct crisis keywords, ensuring that even if the AI misclassifies or goes offline, explicit "
    "indicators of self-harm will always be caught and escalated."
)

add_separator()

add_qa_block(
    "What happens if a student tries to trigger false critical alerts to spam parents or get peers in trouble?",
    "We trust our students not to do that.",
    "We have established robust defense mechanisms against system weaponization: "
    "(1) Bullying reports require Stage 1 manual verification — the counselor must review and verify the report before Stage 2 parent alerts are sent. No automated alerts go to the accused student's parents based on raw reports alone. "
    "(2) Rate limiters restrict submissions (30-sec for vents, 120-sec for bullying reports) to block spam behavior. "
    "(3) The parent SMS for Critical vents is phrased as a neutral 'high-stress wellness check' rather than an accusation, "
    "ensuring safety and prompting standard home check-ins without causing false disciplinary panics."
)

add_separator()

add_qa_block(
    "Why did you use mDeBERTa-v3? Why not standard BERT or RoBERTa?",
    None,
    "DeBERTa-v3 introduces ELECTRA-style replaced token detection pre-training, which He et al. (2021) demonstrated beats "
    "BERT and RoBERTa on all major classification benchmarks while utilizing far fewer parameters. The multilingual variant "
    "(mDeBERTa-v3) runs inference with high contextual accuracy on traditional server CPUs — no expensive GPU clusters needed. "
    "Crucially, its multilingual pre-training captures Taglish code-switching and Philippine Gen-Z slang, whereas monolingual "
    "BERT models fail on code-switched text, as confirmed by Nozza (2022)."
)

add_separator()

add_qa_block(
    "What if a student types sarcasm, e.g., 'KMS because our math quiz is so hard'? Won't this trigger parent panic?",
    None,
    "This was a key focus during our Sprint 3 refinement. The system utilizes multi-dimensional ensemble checks: "
    "(1) DistilRoBERTa extracts emotional dimensions — for casual sarcasm, fear/sadness vectors remain low while surprise/joy/neutral remain moderate, signaling a hyperbolic tone. "
    "(2) MiniLM vector matching measures cosine distance against true clinical crisis anchors — hyperbolic slang does not match the semantic profile of active, planned self-harm. "
    "(3) Sarcasm logic automatically downgrades these to Medium or High (requiring quiet counselor review on the dashboard) rather than triggering a Critical-level parent alarm."
)

add_separator()

add_qa_block(
    "What happens to the data if the school ceases operation or the project terminates?",
    None,
    "All sensitive text is encrypted with AES-256 at the application level before MySQL insertion. Passwords use bcrypt hashing. "
    "Even if the server is compromised, the raw data remains completely unreadable hexadecimal strings because decryption keys "
    "are stored separately in local environment configuration files. Counselors can also soft-delete and purge records, fully "
    "aligning with the student's 'Right to be Forgotten' under RA 10173 (Data Privacy Act of 2012)."
)

add_separator()

add_qa_block(
    "Why Agile methodology? Your system looks straightforward.",
    "Because our professor told us to use Agile.",
    "StudentShield is not a simple, static software package. Building a decoupled hybrid system connecting a PHP backend "
    "with a Python deep-learning microservice requires highly interactive iteration. Agile methodology allowed us to develop "
    "the core foundation in Sprint 1, integrate complex AI models in Sprint 2, tune threshold vectors and sarcasm fallbacks "
    "in Sprint 3 based on initial evaluation data, and deploy the production build in Sprint 4. This iterative approach "
    "prevented architecture failures that a rigid linear model like Waterfall would have missed — for example, we discovered "
    "during Sprint 3 that our initial sarcasm threshold was generating excessive false Critical alerts, which we corrected "
    "before pilot deployment."
)

add_separator()

add_qa_block(
    "What is more dangerous in your system: a False Positive or a False Negative?",
    "Both are equally bad.",
    "In a crisis intervention system, a False Negative — missing a student in genuine critical distress — is far more "
    "dangerous than a False Positive, which merely over-flags a student for counselor review. Therefore, we intentionally "
    "tuned our classification threshold to be highly sensitive (favoring recall over precision). We implemented the Local "
    "Heuristic Fallback in PHP to guarantee that explicit critical indicators are always caught, and we rely on the guidance "
    "counselor's manual Human-in-the-Loop review to filter out false alarms safely."
)

add_separator()

add_qa_block(
    "How does the system handle a student who writes in pure Tagalog or pure Bisaya?",
    "Our system only works for English text.",
    "Our primary threat detection model, mDeBERTa-v3, is a multilingual transformer pre-trained on 100+ languages, "
    "including Filipino. Additionally, our Semantic Crisis Matching stage (MiniLM) uses a crisis anchor database that "
    "includes phrases in English, Tagalog, Ilocano, and Bisaya — for example, 'Gusto nako mamatay' (Bisaya) and "
    "'Ayoko na talaga, pagod na ko' (Tagalog). This multi-dialect coverage was inspired by Cruz and Cheng (2022), who "
    "demonstrated that multilingual transfer learning dramatically outperforms models trained from scratch on low-resource "
    "Philippine languages."
)

add_separator()

add_qa_block(
    "What is FAISS and why did you use it instead of a regular database query?",
    "It's a Facebook thing that searches stuff faster.",
    "FAISS — Facebook AI Similarity Search — is a high-performance library for dense vector similarity search. Unlike "
    "a regular SQL database query that matches exact text, FAISS operates on mathematical vector embeddings. Each vent is "
    "converted into a 384-dimensional vector using MiniLM. FAISS indexes these vectors and retrieves similar past entries "
    "in microseconds. This allows us to calculate the 'Trend Velocity' — the slope of a student's emotional risk trajectory "
    "over 14 days — which is impossible with traditional keyword-based database queries. This approach was inspired by "
    "Ríos and Kavuluru (2023), who demonstrated that embedding-based trajectory analysis catches gradual emotional "
    "deterioration that single-post analysis misses."
)

add_separator()

add_qa_block(
    "What if the school's internet goes down completely?",
    "The system will still work fine.",
    "If the internet connection drops entirely, the FastAPI ML microservice hosted on Azure becomes unreachable. However, "
    "our Two-Tiered Safety Net ensures the PHP application — hosted locally or on a LAN-accessible server — still functions. "
    "The Local Heuristic Fallback (Tier 2) scans submitted text against a crisis keyword dictionary within the PHP layer itself. "
    "Additionally, our SMS gateway uses a Local Android phone on the school's LAN, so SMS alerts to parents can still be sent "
    "without internet if the Android gateway has cellular signal. Email alerts, however, would be queued until connectivity is "
    "restored. This is a documented limitation of our study."
)

add_separator()

add_qa_block(
    "What is your basis for classifying risk levels? Why those specific percentage ranges?",
    "We just divided it equally.",
    "Our risk classification tiers were informed by Faro, Tejada, and Al-Delaimy (2025), whose study on severity classification "
    "of anxiety and depression demonstrated that automated algorithmic classification can achieve over 85% accuracy in identifying "
    "severe and critical psychological flags when using structured alert metrics. We adapted their methodology by mapping our "
    "ensemble model outputs into four distinct tiers: Low (1-30%), Medium (31-60%), High (61-85%), and Critical (86-100%). "
    "These thresholds were then calibrated during Sprint 3 testing using pre-classified Taglish test datasets to minimize false "
    "Critical alerts while maintaining high sensitivity for genuine crises."
)

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#     SECTION 11: RELATED LITERATURE CHEAT SHEET
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('11. Review of Related Literature – Quick Reference Cheat Sheet', level=1)

add_paragraph("Use this table to quickly cite literature during the defense when a panelist challenges a design decision.", italic=True)

lit_rows = [
    ["Elsafoury, Katsigiannis & Ramzan (2023)", "Deep learning for cyberbullying detection", "Justified using Transformer architecture over keyword matching"],
    ["Mazari, Boudoukhani & Djeffal (2023)", "BERT ensemble for multi-aspect hate speech (98.63% ROC-AUC)", "Justified multi-label risk classification instead of binary"],
    ["Zhang, Schoene, Ji & Ananiadou (2022)", "NLP for mental illness detection from non-clinical text", "Justified analyzing informal Taglish student text"],
    ["He, Gao & Chen (2021)", "DeBERTa-v3 with ELECTRA-style pre-training", "Justified selecting mDeBERTa-v3 for CPU-efficient accuracy"],
    ["Saleh, Alhothali & Moria (2023)", "BERT + custom hate speech embeddings", "Justified domain-specific fine-tuning for Filipino slang"],
    ["Smith (2022)", "Bullying vs. cyberbullying dual-channel consequences", "Justified collecting both informal vents AND formal reports"],
    ["Mnassri et al. (2022)", "BERT ensemble voting reduces false positives", "Justified dual-model second-opinion (mDeBERTa + DistilRoBERTa)"],
    ["UNICEF (2021)", "AI for Children: 9 ethical requirements", "Justified Human-in-the-Loop design; AI ≠ replacement for counselor"],
    ["Buguina & Buguina (2023)", "Cyberbullying hesitancy in Philippine schools", "Justified conditional anonymity and encrypted reporting"],
    ["EDCOM 2 (2024)", "Bullying prevalence above OECD average; lack of digital tools", "Justified building an early-warning ML platform for schools"],
    ["DepEd (2022)", "Revised IRR of RA 10627 covering cyberbullying", "Justified using StudentShield as a compliance tool"],
    ["Billones & Sybingco (2023)", "SMS reliability in Philippine emergency alerting", "Justified SMS as primary parent alert channel over email"],
    ["De Leon & Reyes (2023)", "40% more disclosures with anonymous digital portals", "Justified conditional anonymity as default setting"],
    ["DOH (2023)", "Inconsistent Mental Health Act implementation in schools", "Justified automated screening to supplement limited guidance staff"],
    ["Nozza (2022)", "Hate speech models fail on code-switched text", "Justified selecting multilingual mDeBERTa-v3 for Taglish"],
    ["Cruz & Cheng (2022)", "Transfer learning for Filipino language NLP", "Justified pre-trained multilingual transformer approach"],
    ["Ríos & Kavuluru (2023)", "Vector-based emotional trajectory tracking", "Justified FAISS 14-day behavioral trajectory analysis"],
    ["Koulouri, Lauria & Macredie (2022)", "Non-judgmental UI increases student disclosure", "Justified warm, informal \"Safe Space\" portal design"],
    ["Ophir et al. (2022)", "NLP screening comparable to clinical instruments", "Justified AI as \"first-pass\" filter, not clinical diagnostic"],
    ["Faro, Tejada & Al-Delaimy (2025)", "ML for severity classification of anxiety/depression", "Justified automated risk tier classification (Low–Critical)"],
    ["Almomani et al. (2024)", "ProTect: Fast hybrid cyberbullying detection", "Justified FastAPI microservice for sub-second classification"],
    ["Maity et al. (2022)", "Multitask cyberbullying: sentiment + emotion + sarcasm", "Justified emotional risk percentage scoring (0–100)"],
    ["Pinera et al. (2022)", "RA 10627 compliance gaps in Philippine schools", "Justified automated documentation and case timestamping"],
    ["Cabalquinto (2022)", "Filipino parents lack digital monitoring skills", "Justified plain-language automated SMS alerts to parents"],
]
add_table(["Author(s) & Year", "Key Finding", "How It Supports StudentShield"], lit_rows)

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#              SECTION 12: DEFINITION OF TERMS
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('12. Key Definition of Terms', level=1)

terms = [
    ("AES-256 (Advanced Encryption Standard – 256-bit)", "A symmetric encryption algorithm used to protect sensitive data at rest. StudentShield encrypts all student vent texts and bullying reports before database insertion."),
    ("Agile Scrum", "An iterative software development methodology that divides work into short cycles (sprints), allowing frequent feedback and adaptation — used in this study's SDLC."),
    ("bcrypt", "A password hashing function designed to be computationally expensive, making brute-force attacks impractical. Used for all user password storage in StudentShield."),
    ("CodeIgniter 4 (CI4)", "A lightweight PHP framework providing MVC architecture, CSRF protection, and SQL injection prevention — used as the web application backend."),
    ("Conditional Anonymity", "A privacy model where students remain anonymous during Low/Medium sessions. Anonymity is lifted for authorized counselors only when a Critical life-threatening risk is detected."),
    ("Confusion Matrix", "A statistical table used to evaluate ML classification performance by comparing predicted labels against actual labels (True Positives, False Positives, True Negatives, False Negatives)."),
    ("Cosine Similarity", "A mathematical measure of similarity between two vectors. Used by MiniLM to compare student text embeddings against crisis anchor embeddings."),
    ("Cyberbullying", "The use of digital devices, platforms, or technology to harass, threaten, or humiliate another person, often repeatedly and with intent to harm."),
    ("Data Privacy Act of 2012 (RA 10173)", "Philippine law governing the processing of personal information, including requirements for data encryption, consent, and the right to be forgotten."),
    ("DeBERTa-v3 (Decoding-enhanced BERT with Disentangled Attention)", "An advanced Transformer model using ELECTRA-style pre-training for superior text classification with fewer resources."),
    ("DistilRoBERTa", "A distilled (compressed) version of the RoBERTa language model, used in StudentShield for granular emotion extraction across 7 emotional categories."),
    ("Docker", "A containerization platform that packages applications and dependencies into portable containers. Used to deploy the FastAPI ML microservice on Azure."),
    ("FAISS (Facebook AI Similarity Search)", "A library for efficient dense vector similarity search. Used to track 14-day behavioral trajectories by indexing and comparing vent embeddings."),
    ("False Negative", "When the AI fails to detect a genuine critical case — the most dangerous error type in a crisis intervention system."),
    ("False Positive", "When the AI incorrectly flags a non-critical case as critical — undesirable but less dangerous than a False Negative."),
    ("FastAPI", "A high-performance Python web framework used for building the ML microservice that processes student text through the neural pipeline."),
    ("F1-Score", "The harmonic mean of Precision and Recall, providing a balanced measure of a classifier's accuracy."),
    ("Golden Hour", "The critical time window immediately after a student expresses distress, when intervention is most effective and the student is most receptive to support."),
    ("Human-in-the-Loop", "A design principle where AI systems assist but do not replace human decision-makers — counselors make all final intervention decisions."),
    ("ISO/IEC 25010", "An international standard defining software quality characteristics (functional suitability, usability, performance efficiency, security) used as the evaluation framework."),
    ("Likert Scale", "A 5-point psychometric response scale used in surveys (1 = Strongly Disagree to 5 = Strongly Agree). Used in the ISO/IEC 25010 evaluation questionnaire."),
    ("mDeBERTa-v3", "The multilingual variant of DeBERTa-v3, pre-trained on 100+ languages. Serves as the primary distress detection model in StudentShield."),
    ("MiniLM (paraphrase-multilingual-MiniLM-L12-v2)", "A lightweight multilingual sentence embedding model used for semantic crisis matching via cosine similarity."),
    ("Natural Language Processing (NLP)", "A field of artificial intelligence focused on enabling computers to understand, interpret, and generate human language."),
    ("Precision", "The ratio of true positive predictions to all positive predictions — measures how many of the AI's alerts are actually correct."),
    ("Recall (Sensitivity)", "The ratio of true positive predictions to all actual positives — measures how many genuine cases the AI successfully catches."),
    ("Republic Act No. 10627", "The Anti-Bullying Act of 2013 — Philippine law requiring schools to adopt policies against bullying and cyberbullying, with 2022 revised IRR."),
    ("SMTP (Simple Mail Transfer Protocol)", "The internet standard protocol for email transmission. Used with Gmail App Passwords for automated email alerting."),
    ("Slovin's Formula", "A statistical formula (n = N / (1 + Ne²)) used to determine the minimum sample size for survey research."),
    ("Stratified Random Sampling", "A sampling method that divides the population into subgroups (strata) and randomly samples from each to ensure proportional representation."),
    ("Taglish", "A form of code-switching that mixes Tagalog and English words within a single sentence — the dominant communication style of Filipino students."),
    ("Transformer", "A neural network architecture (introduced by Vaswani et al., 2017) that uses self-attention mechanisms to understand relationships between words. The foundation of BERT, RoBERTa, and DeBERTa."),
    ("Trend Velocity", "A StudentShield metric measuring the slope of emotional risk escalation over a 14-day window using FAISS vector trajectories."),
    ("Two-Tiered Safety Net", "StudentShield's redundancy design: Tier 1 (deep semantic ML analysis) + Tier 2 (local PHP heuristic keyword fallback). The higher risk score always wins."),
    ("Vector Embedding", "A mathematical representation of text as a high-dimensional numerical array, enabling semantic comparison between sentences."),
    ("Weighted Mean", "A statistical measure calculated as WM = (Σfx) / N, used to interpret Likert-scale survey results."),
]
for term, definition in terms:
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(6)
    rt = p.add_run(f"{term} – ")
    rt.bold = True
    rt.font.name = 'Garamond'
    rt.font.size = Pt(11)
    rd = p.add_run(definition)
    rd.font.name = 'Garamond'
    rd.font.size = Pt(11)

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#              SECTION 13: LAWS & LEGAL COMPLIANCE
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('13. Laws & Legal Compliance Summary', level=1)

add_paragraph("Be prepared to cite these laws if panelists ask about legal basis, data privacy, or regulatory compliance.", italic=True)

laws = [
    ("Republic Act No. 10627 – Anti-Bullying Act of 2013",
     "Requires all elementary and secondary schools (public and private) to adopt policies against bullying. "
     "The 2022 revised IRR explicitly extends coverage to cyberbullying occurring outside school premises and online harassment. "
     "Schools are directed to implement digital tools for identifying and documenting bullying incidents. "
     "StudentShield serves as a direct compliance tool — it automatically logs, timestamps, and classifies all flagged incidents."),

    ("Republic Act No. 10173 – Data Privacy Act of 2012",
     "Governs the processing of personal information in the Philippines. Requires: (1) legitimate purpose for data collection, "
     "(2) proportionality of data collected, (3) appropriate security measures (encryption), and (4) the data subject's right "
     "to access and delete their data. StudentShield complies by using AES-256 encryption for all sensitive text, bcrypt for "
     "passwords, role-based access control, and audit logging for every data access event."),

    ("Republic Act No. 11036 – Philippine Mental Health Act",
     "Mandates the integration of mental health programs in government services including educational institutions. "
     "DOH (2023) found that implementation is inconsistent — most schools lack adequate screening capacity. "
     "StudentShield addresses this gap as an automated NLP-based screening tool that supplements limited guidance staff resources."),

    ("UNICEF 2021 – Policy Guidance on AI for Children",
     "Establishes 9 ethical requirements for AI systems that directly impact minors: fairness, transparency, child data protection, "
     "and mandatory human oversight. StudentShield complies through the Human-in-the-Loop principle — the AI flags and prioritizes "
     "but never makes intervention decisions autonomously. Parent SMS messages are phrased as neutral wellness checks, not diagnoses."),
]
for title, desc in laws:
    doc.add_heading(title, level=2)
    add_paragraph(desc)

doc.add_page_break()

# ══════════════════════════════════════════════════════════════════════
#              SECTION 14: PRACTICAL DEFENSE TIPS
# ══════════════════════════════════════════════════════════════════════
doc.add_heading('14. Practical Oral Defense Tips for the Team', level=1)

doc.add_heading('Tip 1: Master the "Baton Pass"', level=2)
add_paragraph("Assign expert roles to each member so the panel sees unity and deep specialization:")
add_bullet("Melgarejo / Ramos → Systems Development & Web Stack (PHP, CI4, database, SMTP, SMS gateway, deployment)")
add_bullet("Meneses / Enriquez → AI Integration & Data Science (mDeBERTa, DistilRoBERTa, FAISS, sarcasm logic, model weights)")
add_bullet("Urayanza → Methodology, Usability & Compliance (Agile sprints, Slovin's formula, ISO/IEC 25010, RA 10173)")

doc.add_heading('Tip 2: Defend the Limitations Confidently', level=2)
add_paragraph("If the panel says \"Why doesn't the system work offline?\" or \"Why no live video counseling?\"")
p = doc.add_paragraph()
p.paragraph_format.left_indent = Inches(0.3)
r = p.add_run(
    "\"That is a recognized limitation of our study. To avoid scope creep and keep the project financially sustainable "
    "for the school, we restricted the system to text-based submissions and SMS/Email alerting, which fits within the "
    "school's existing infrastructure. Expanding to real-time chat, offline syncing, or video counseling is recommended "
    "for future research.\""
)
r.italic = True
r.font.name = 'Garamond'
r.font.size = Pt(11)

doc.add_heading('Tip 3: Own the Confusion Matrix', level=2)
add_paragraph(
    "If the panel asks \"What is more dangerous: a False Positive or a False Negative?\" — Answer: "
    "\"In a crisis intervention system, a False Negative (missing a student in genuine critical distress) is far more "
    "dangerous than a False Positive (over-flagging a student for counselor review). Therefore, we intentionally tuned "
    "our classification threshold to be highly sensitive, favoring recall over precision. The Local Heuristic Fallback "
    "in PHP guarantees explicit critical indicators are always caught, and we rely on the counselor's manual review "
    "to safely filter out false alarms.\""
)

doc.add_heading('Tip 4: Keep Answers Technical and Academic', level=2)
add_paragraph("Avoid casual phrases. Always cite your literature and methods:")
add_bullet("\"Based on the findings of He et al. (2021)...\"")
add_bullet("\"In compliance with the Data Privacy Act of 2012 (RA 10173)...\"")
add_bullet("\"Consistent with the Agile SDLC framework we adopted...\"")
add_bullet("\"As supported by Nozza (2022), monolingual models fail on code-switched text...\"")

doc.add_heading('Tip 5: If You Don\'t Know, Redirect Honestly', level=2)
add_paragraph(
    "If a panelist asks a question you truly cannot answer, do not bluff. Say: "
    "\"That is a valid point, Sir/Ma'am. Our study did not cover [X] because it falls outside our defined scope. "
    "However, we believe this would be an excellent direction for future research.\" — This is far more respectable "
    "than fabricating an answer that the panelist can immediately debunk."
)

doc.add_heading('Tip 6: Rehearse the Demo Flow', level=2)
add_paragraph("Practice the live demo in this exact sequence to maximize impact:")
add_numbered("Log in as a student. Show the clean, inviting Safe Space portal.", 1)
add_numbered("Submit a mild vent (\"Ang hirap ng buhay minsan\"). Show it classified as LOW/MEDIUM on the counselor dashboard.", 2)
add_numbered("Submit a critical vent (\"Ayoko na, gusto ko na lang mawala\"). Show the CRITICAL classification, the automatic SMS sent to the parent's phone, and the email alert — all in real-time.", 3)
add_numbered("Show the counselor dashboard: pie chart, trend chart, and the student's 14-day risk timeline.", 4)
add_numbered("Submit a bullying report. Show Stage 1 counselor alert. Then click \"Verify & Alert Parents\" to show Stage 2.", 5)
add_numbered("Open the audit log to show all actions are permanently recorded with IP, User Agent, and timestamp.", 6)
add_numbered("Finally, open the MySQL database directly and show that all vent texts are stored as encrypted AES-256 ciphertext — proving that even database admins cannot read raw student data.", 7)

# ══════════════════════════════════════════════════════════════════════
#                     FINAL PAGE
# ══════════════════════════════════════════════════════════════════════
doc.add_page_break()
for _ in range(6):
    doc.add_paragraph()

add_bold_paragraph("— END OF DEFENSE REVIEWER —", size=16, color=RGBColor(0x0B, 0x2E, 0x5E), align=WD_ALIGN_PARAGRAPH.CENTER)

doc.add_paragraph()
p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
r = p.add_run("Good luck, Team StudentShield. You built something that matters. Now defend it like you mean it.")
r.italic = True
r.font.name = 'Garamond'
r.font.size = Pt(12)
r.font.color.rgb = RGBColor(0x55, 0x55, 0x55)

# ── Save ──
output_path = os.path.join(r"d:\laragon\www\student shield", "StudentShield_Defense_Reviewer.docx")
doc.save(output_path)
print(f"✅ Document saved to: {output_path}")
