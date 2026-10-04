"""StudentShield conceptual framework, revised in the original IPO format."""

from pathlib import Path
from PIL import Image, ImageDraw, ImageFont


OUT = Path(__file__).with_name("figure-ipo-current.png")
S = 3
W, H = 1200, 1460
REGULAR = "C:/Windows/Fonts/times.ttf"
BOLD = "C:/Windows/Fonts/timesbd.ttf"
im = Image.new("RGB", (W*S, H*S), "white")
d = ImageDraw.Draw(im)


def font(size=23, bold=False):
    return ImageFont.truetype(BOLD if bold else REGULAR, size*S)


def text(x, y, value, size=23, bold=False, anchor="la"):
    d.text((int(x*S), int(y*S)), value, font=font(size, bold),
           fill="black", anchor=anchor)


def line(points, width=2):
    d.line([(int(x*S), int(y*S)) for x, y in points], fill="black",
           width=width*S)


def box(x1, y1, x2, y2):
    d.rectangle((x1*S, y1*S, x2*S, y2*S), outline="black", width=2*S)


def wrap(value, max_width, size=23):
    words = value.split()
    lines, current = [], ""
    for word in words:
        candidate = f"{current} {word}".strip()
        if d.textlength(candidate, font=font(size)) <= max_width*S:
            current = candidate
        else:
            if current:
                lines.append(current)
            current = word
    if current:
        lines.append(current)
    return lines


def column(x1, x2, title, groups):
    text((x1+x2)/2, 43, title, 30, True, "mm")
    box(x1, 73, x2, 1185)
    y = 98
    for heading, items in groups:
        text((x1+x2)/2, y, heading, 23, True, "mt")
        y += 36
        for item in items:
            lines = wrap(item, x2-x1-64)
            text(x1+22, y, "•", 24)
            for offset, part in enumerate(lines):
                text(x1+43, y+offset*28, part)
            y += len(lines)*28 + 10
        y += 12
    if y > 1167:
        raise RuntimeError(f"{title} content ends at {y}; column overflows")
    print(f"{title}: content ends at {y}")


column(25, 398, "INPUT", [
    ("USER ACTORS", [
        "Junior High School students submit vents and reports.",
        "Counselors review cases and coordinate follow-up.",
        "Super Administrators manage users and review audit logs.",
        "Parents or guardians are eligible notification recipients.",
    ]),
    ("SYSTEM DATA", [
        "Vent text, bullying reports, and optional evidence.",
        "Profiles, selected feelings, contact details, and risk history.",
        "Counseling requests and per-session Meet links.",
    ]),
    ("DEVELOPMENT SOFTWARE", [
        "CodeIgniter 4 / PHP web application.",
        "FastAPI / PyTorch with mDeBERTa and GoEmotions.",
        "MySQL / MariaDB and browser interface.",
        "Configured SMS and email paths; external Google Meet.",
    ]),
    ("HARDWARE / ACCESS", [
        "Browser device and internet connection.",
    ]),
])

column(413, 786, "PROCESS", [
    ("PLANNING & ANALYSIS", [
        "Define SVBSM users, reporting needs, and follow-up workflow.",
    ]),
    ("SYSTEM DESIGN", [
        "Map roles, submissions, risk bands, and alert rules.",
    ]),
    ("DEVELOPMENT", [
        "Build the web application and screening service.",
        "Encrypt vent text and use recent risk-label history.",
        "Let staff review cases, verify reports, and post Meet links.",
    ]),
    ("SCREENING & ALERTS", [
        "Use ML results with PHP crisis rules and fallback.",
        "Attempt conditional counselor and parent notifications.",
    ]),
    ("TESTING & EVALUATION", [
        "Check access, submissions, screening, and alert handling.",
    ]),
    ("DEPLOYMENT & REVIEW", [
        "Release updates and revise features from test findings and feedback.",
    ]),
])

column(801, 1174, "OUTPUT", [
    ("STUDENTSHIELD SYSTEM", [
        "Account-based Safe Space and bullying reporting.",
        "Low, Medium, High, or Critical screening bands.",
        "Staff case views, decisions, and student risk history.",
        "Conditional SMS and email alert attempts.",
        "Assigned access to an external Google Meet session.",
    ]),
    ("SYSTEM DELIVERABLES", [
        "Web application and FastAPI screening service.",
        "Case review and audit-log views for authorized staff.",
    ]),
])

# Match the old figure's Output-to-Evaluation connector without crossing text.
line([(988, 1185), (988, 1240), (885, 1240), (885, 1275)])
d.polygon([(885*S, 1275*S), (875*S, 1259*S), (895*S, 1259*S)],
          fill="black")
box(285, 1275, 885, 1410)
text(585, 1299, "EVALUATION", 27, True, "mm")
text(315, 1332, "Planned review of usability, screening results,", 22)
text(315, 1364, "and notification handling with SVBSM users.", 22)

im.convert("L").point(lambda value: 255 if value >= 170 else 0,
                       mode="1").save(OUT)
print(OUT)
