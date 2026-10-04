"""Draw the current StudentShield use cases as one monochrome UML diagram.

The figure is sized for a 398 pt-wide portrait thesis page. At that width, its
case-label font is approximately 10.5–11 pt. All use cases are supported by the
current web application; the parent is an external notification recipient.
"""

from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


HERE = Path(__file__).resolve().parent
OUTPUT = HERE / "figure-03-unified-use-case.png"
WIDTH, HEIGHT = 3000, 3200
BLACK = (0, 0, 0)
WHITE = (255, 255, 255)
SCALE_PT = 398 / WIDTH


def font(size: int, bold: bool = False) -> ImageFont.FreeTypeFont:
    name = "timesbd.ttf" if bold else "times.ttf"
    path = Path("C:/Windows/Fonts") / name
    return ImageFont.truetype(str(path), size)


img = Image.new("RGB", (WIDTH, HEIGHT), WHITE)
draw = ImageDraw.Draw(img)

# One system boundary. Leave generous side corridors for association lines.
boundary = (500, 130, 2500, 3090)
draw.rectangle(boundary, outline=BLACK, width=8)
draw.text((1500, 235), "StudentShield Web Application", font=font(92, True),
          fill=BLACK, anchor="mm")


def case(y: int, label: str) -> None:
    radius_y = 108 if "\n" in label else 77
    box = (840, y - radius_y, 2160, y + radius_y)
    draw.ellipse(box, outline=BLACK, width=7)
    lines = label.split("\n")
    chosen = font(82)
    for size in range(82, 69, -2):
        candidate = font(size)
        if all(draw.textbbox((0, 0), line, font=candidate)[2] <= 1170 for line in lines):
            chosen = candidate
            break
    if len(lines) == 1:
        draw.text((1500, y), label, font=chosen, fill=BLACK, anchor="mm")
    else:
        line_gap = 80
        for n, line in enumerate(lines):
            draw.text((1500, y + (n - 0.5) * line_gap), line,
                      font=chosen, fill=BLACK, anchor="mm")


student_cases = [
    (430, "Register / sign in"),
    (650, "Submit vent"),
    (870, "File bullying report"),
    (1090, "Request counseling /\nopen assigned Meet link"),
]
parent_case = (1350, "Conditional parent alert attempt")
shared_staff_cases = [
    (1630, "Staff sign in"),
    (1840, "Approve student registration"),
    (2050, "Review vents and reports"),
    (2260, "Verify or reject report"),
    (2470, "Record case notes and status"),
    (2680, "Publish new Meet link"),
]
admin_case = (2930, "Manage users / review audit logs")


def association(x0: int, y0: int, x1: int, y1: int) -> None:
    draw.line((x0, y0, x1, y1), fill=BLACK, width=6)


# Direct associations from opposite sides of shared staff use cases. Fan lines
# remain in the empty corridors and never pass through labels or ellipses.
for y, _ in student_cases:
    association(332, 687, 840, y)
association(2658, 1287, 2160, parent_case[0])
for y, _ in shared_staff_cases:
    association(332, 2167, 840, y)
    association(2658, 2467, 2160, y)
association(2658, 2467, 2160, admin_case[0])

for y, label in student_cases + [parent_case] + shared_staff_cases + [admin_case]:
    case(y, label)


def actor(x: int, head_y: int, label: str, above: bool = False) -> None:
    r = 48
    draw.ellipse((x - r, head_y - r, x + r, head_y + r), outline=BLACK, width=7)
    draw.line((x, head_y + r, x, head_y + 175), fill=BLACK, width=8)
    draw.line((x - 82, head_y + 102, x + 82, head_y + 102), fill=BLACK, width=8)
    draw.line((x, head_y + 175, x - 75, head_y + 265), fill=BLACK, width=8)
    draw.line((x, head_y + 175, x + 75, head_y + 265), fill=BLACK, width=8)
    label_y = head_y - 130 if above else head_y + 322
    for n, line in enumerate(label.split("\n")):
        draw.text((x, label_y + n * 76), line, font=font(70), fill=BLACK, anchor="mm")


actor(250, 585, "Student", above=True)
actor(2740, 1185, "Parent /\nGuardian")
actor(250, 2065, "Counselor", above=True)
actor(2740, 2365, "Super\nAdministrator")

img.save(OUTPUT, dpi=(300, 300), optimize=True)
print(f"{OUTPUT}\n{WIDTH} x {HEIGHT} px; displayed at 398 pt wide: "
      f"{HEIGHT * SCALE_PT:.0f} pt high, 82 px ~ {82 * SCALE_PT:.1f} pt")
