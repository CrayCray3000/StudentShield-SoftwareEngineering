from pathlib import Path
from reportlab.lib import colors
from reportlab.lib.enums import TA_LEFT
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, KeepTogether
from pypdf import PdfReader
import pypdfium2 as pdfium

ROOT = Path(__file__).resolve().parent
OUTPUT = ROOT / 'StudentShield_UI_Suggestions.pdf'
pdfmetrics.registerFont(TTFont('Arial', 'C:/Windows/Fonts/arial.ttf'))
pdfmetrics.registerFont(TTFont('Arial-Bold', 'C:/Windows/Fonts/arialbd.ttf'))
pdfmetrics.registerFontFamily('Arial', normal='Arial', bold='Arial-Bold')

title = ParagraphStyle('Title', fontName='Arial-Bold', fontSize=23,
                       leading=28, textColor=colors.black, spaceAfter=5)
subtitle = ParagraphStyle('Subtitle', fontName='Arial', fontSize=10,
                          leading=14, textColor=colors.HexColor('#454545'), spaceAfter=18)
body = ParagraphStyle('Body', fontName='Arial', fontSize=11, leading=16,
                      alignment=TA_LEFT, textColor=colors.black)
heading = ParagraphStyle('Heading', fontName='Arial-Bold', fontSize=12,
                         leading=16, spaceAfter=5, textColor=colors.black)

suggestions = [
    ('1. Student action selection',
     'Move the Meet card below Safe Space and Incident Report. Shorten the introduction '
     'and reduce card padding so both choices are easier to see on a phone. Keep the '
     'two choices equally prominent.'),
    ('2. Venting page',
     'Place the heading, short instruction, and writing field first. Move the quote '
     'and Meet card lower or make them smaller. Align the writing field, emotion '
     'choices, preference option, and submit button within one content column.'),
    ('3. Fonts and text',
     'Use Outfit for headings and Inter for text, fields, and buttons. Make the Meet '
     'card follow those fonts and remove the separate serif treatment from the quote. '
     'Aim for 16 px body text and 14 px helper text on the website, with darker secondary text.'),
    ('4. Staff dashboard',
     'Place the vent, incident-report, and session sections before the charts. Keep '
     'visible record lists shorter and provide clear section navigation so staff '
     'can reach each section without scrolling through the whole vent feed.'),
    ('5. Consistent components',
     'Use the same logo treatment, header spacing, button heights, borders, and corner '
     'rounding across related pages. Aim for 44-48 px control heights and 16-24 px '
     'phone content padding. Give the Incident Report page a matching subtitle.'),
    ('6. Mobile layout',
     'Keep navigation and the session dropdown within the screen. Clip decorative '
     'background shapes to prevent sideways scrolling. Let wide staff tables scroll '
     'inside their own containers rather than widening the whole page.'),
]

def finish_page(canvas, doc):
    canvas.saveState()
    canvas.setStrokeColor(colors.HexColor('#B9B9B9'))
    canvas.setLineWidth(0.5)
    canvas.line(50, 43, A4[0] - 50, 43)
    canvas.setFont('Arial', 8)
    canvas.setFillColor(colors.HexColor('#454545'))
    canvas.drawString(50, 30, 'StudentShield | UI suggestions')
    canvas.drawRightString(A4[0] - 50, 30, str(doc.page))
    canvas.restoreState()

doc = SimpleDocTemplate(str(OUTPUT), pagesize=A4, leftMargin=50, rightMargin=50,
                        topMargin=44, bottomMargin=58,
                        title='StudentShield UI Suggestions', author='StudentShield',
                        subject='Visual design suggestions for the StudentShield capstone')
story = [Paragraph('StudentShield', title),
         Paragraph('UI suggestions | September 30, 2026', subtitle),
         Paragraph('Keep the school logo, green and gold identity, light form surfaces, '
                   'and the two student action cards.', body), Spacer(1, 19)]
for section_title, text in suggestions:
    story.append(KeepTogether([Paragraph(section_title, heading),
                              Paragraph(text, body), Spacer(1, 15)]))
story.append(Paragraph('<b>Start with the student chooser, then the Vent layout.</b>', body))
doc.build(story, onFirstPage=finish_page, onLaterPages=finish_page)

reader = PdfReader(str(OUTPUT))
assert len(reader.pages) == 1, f'Expected one page, got {len(reader.pages)}'
text = reader.pages[0].extract_text()
assert all(section_title in text for section_title, _ in suggestions)
assert 'Start with the student chooser' in text
pdf = pdfium.PdfDocument(str(OUTPUT))
page = pdf[0]
image = page.render(scale=1.6).to_pil()
image.save(ROOT / 'ui_suggestions_preview.png')
print(f'Created {OUTPUT}')
print(f'Verified one page and all six suggestions. Size: {OUTPUT.stat().st_size} bytes.')
