from pathlib import Path
from reportlab.pdfgen import canvas
from reportlab.lib.colors import HexColor
from reportlab.lib.pagesizes import A4
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.platypus import Paragraph
from reportlab.lib.styles import ParagraphStyle
import fitz

ROOT = Path(__file__).resolve().parent
OUT = ROOT / 'StudentShield_Suggestions.pdf'
pdfmetrics.registerFont(TTFont('Body', 'C:/Windows/Fonts/calibri.ttf'))
pdfmetrics.registerFont(TTFont('Bold', 'C:/Windows/Fonts/calibrib.ttf'))
W, H = A4
M = 57
GREEN = HexColor('#17643B')
INK = HexColor('#202823')
MUTED = HexColor('#59635C')
RULE = HexColor('#D9E0DA')
c = canvas.Canvas(str(OUT), pagesize=A4)
c.setTitle('StudentShield | UI and Security Suggestions')
c.setAuthor('StudentShield')
c.setSubject('UI recommendations and proposed security checks for StudentShield')

def para(text, top, size=11.5, leading=15.5, color=INK, font='Body', width=W-2*M):
    style = ParagraphStyle('text', fontName=font, fontSize=size, leading=leading, textColor=color)
    p = Paragraph(text, style)
    _, height = p.wrap(width, H)
    p.drawOn(c, M, top-height)
    return top-height

def line(y, color=RULE):
    c.setStrokeColor(color)
    c.setLineWidth(.65)
    c.line(M, y, W-M, y)

# Title page: restrained school colors, no stock artwork or invented credentials.
c.setFillColor(GREEN)
c.rect(M, H-89, 42, 4, fill=1, stroke=0)
para('STUDENTSHIELD', H-112, 12, 16, GREEN, 'Bold')
y = para('UI and Security<br/>Suggestions', H-245, 35, 40, INK, 'Bold')
para('Student pages and staff dashboard', y-23, 15, 21, MUTED)
line(H-418)
title_bottom = para('StudentShield: Machine Learning-Based Emotional Distress Detection and Bullying Reporting System with Automated Parental Alerts for Junior High School Students at Saint Vincent Blessed School of Manila', H-445, 12, 17)
para('Capstone project', title_bottom-12, 11.5, 16, MUTED)
para('Website reviewed: studentshield.app<br/>Review date: September 30, 2026<br/>Report revised: October 1, 2026', 217, 11, 17, MUTED)
para('UI recommendations from the September 30 review. Lighter login colors and security suggestions added October 1. Security items are proposed checks, not findings from a security audit.', 124, 10, 14, MUTED, width=410)
c.showPage()

para('STUDENTSHIELD', H-43, 9, 12, GREEN, 'Bold')
c.setFont('Body', 9)
c.setFillColor(MUTED)
c.drawRightString(W-M, H-53, 'UI review  /  September 30, 2026')
line(H-66)
y = para('Recommended UI changes', H-89, 23, 28, INK, 'Bold')
y = para('Keep the school logo, green and gold identity, light form surfaces, and the two student action cards. Focus the revisions on page order, spacing, text, and mobile layout.', y-14, 11.5, 15.5)

sections = [
('1. Student action selection',
 'Move the Meet card below Safe Space and Incident Report. Shorten the introduction and reduce card padding so students can see both choices more easily on a phone. Give the two choices equal visual weight.'),
('2. Venting page',
 'Place the heading, a short instruction, and the writing field first. Move the quote and Meet card lower on the page or make them smaller. Keep the writing field, emotion choices, notification preference, and submit button aligned within one content column.'),
('3. Fonts and text',
 'Use Outfit for headings and Inter for body text, fields, and buttons. Apply the same fonts to the Meet card and replace the quote’s separate serif font. Aim for 16 px body text and 14 px helper text on the website. Darken secondary text where it is difficult to read.'),
('4. Staff dashboard',
 'Place the vent, incident-report, and session sections before the charts. Shorten the visible record lists and provide clear section navigation, so staff can reach a section without scrolling through the whole vent feed.'),
('5. Consistent components',
 'Use the same logo treatment, header spacing, button heights, borders, and corner rounding across related pages. Aim for control heights of 44–48 px and phone content padding of 16–24 px. Add a matching subtitle to the Incident Report page.'),
('6. Mobile layout',
 'Keep navigation and the session dropdown inside the screen. Clip decorative background shapes to prevent sideways scrolling. Let wide staff tables scroll within their own containers so they do not widen the entire page.'),
('7. Student and admin login colors',
 'Use a lighter school green on both login pages, following the client’s request. Replace the dark background gradients with a brighter green close to the school banner. Keep a slightly darker green for form fields and clear, high-contrast text. Apply the same shades to both pages.'),
]
for heading, text in sections:
    y = para(heading, y-17, 12, 16, GREEN, 'Bold')
    y = para(text, y-5, 11, 14.5)

assert y > 85, f'Content too close to footer: {y}'
line(64)
para('Suggested order: student action selection, then the Vent layout.', 51, 9, 12, MUTED)
c.setFont('Body', 9)
c.drawRightString(W-M, 39, '2')
c.showPage()

para('STUDENTSHIELD', H-43, 9, 12, GREEN, 'Bold')
c.setFont('Body', 9)
c.setFillColor(MUTED)
c.drawRightString(W-M, H-53, 'Security suggestions  /  October 1, 2026')
line(H-66)
y = para('Recommended security checks', H-89, 23, 28, INK, 'Bold')
y = para('These checks focus on student records, staff accounts, and shared computers. Confirm the existing controls before making changes. No security testing was performed for this addition.', y-14, 11.5, 15.5)
security = [
('1. Login and email verification',
 'Keep email verification for staff accounts. Check that repeated password guesses, incorrect codes, and resend requests are limited. Codes should expire and work only once. Use a general login error that does not reveal whether an account exists. Test these controls with designated test accounts. [1]'),
('2. Sessions on shared computers',
 'Keep Sign Out easy to find on student and staff pages. Confirm that signing out ends the session on the server and that protected pages require login afterward. Use server-enforced idle timeouts, with a warning before expiry where practical. Explain that “Trust this device” is for a personal device, and leave it unchecked by default. [2]'),
('3. Access to student records',
 'Check permissions on every request for vents, incident reports, attachments, and account management. A student should not gain access to another student’s records by changing an address or record number. Staff access should follow the school’s approved responsibilities. Hiding a button alone does not restrict access. [3]'),
('4. Counseling meeting links',
 'Check that only the intended student and authorized staff can retrieve a session’s Meet link through StudentShield. [3] Prefer a separate meeting for each consultation instead of one link shared by all students. The counselor should verify who enters the call using the meeting service’s available admission controls. Restricting a link inside StudentShield cannot prevent someone from forwarding it.'),
]
for heading, text in security:
    y = para(heading, y-21, 12, 16, GREEN, 'Bold')
    y = para(text, y-6, 11.5, 15.5)
y = para('References', y-27, 11, 15, GREEN, 'Bold')
refs = [
('1', 'OWASP: Authentication Cheat Sheet', 'Authentication_Cheat_Sheet'),
('2', 'OWASP: Session Management Cheat Sheet', 'Session_Management_Cheat_Sheet'),
('3', 'OWASP: Authorization Cheat Sheet', 'Authorization_Cheat_Sheet'),
]
for number, title, slug in refs:
    y = para(f'[{number}] <link href="https://cheatsheetseries.owasp.org/cheatsheets/{slug}.html" color="#17643B">{title}</link>', y-6, 9.5, 13, MUTED)
assert y > 85, f'Security content too close to footer: {y}'
line(64)
para('Use test accounts when checking access and session behavior.', 51, 9, 12, MUTED)
c.setFont('Body', 9)
c.drawRightString(W-M, 39, '3')
c.save()

doc = fitz.open(OUT)
assert len(doc) == 3
for i, page in enumerate(doc):
    page.get_pixmap(matrix=fitz.Matrix(1.3, 1.3)).save(ROOT / f'page-{i+1}.png')
    for block in page.get_text('blocks'):
        assert block[0] >= M-1 and block[2] <= W-M+1, (i, block)
        assert block[1] >= 20 and block[3] <= H-20, (i, block)
print(f'Created {OUT}; {len(doc)} pages; body ends at {y:.1f} pt.')

