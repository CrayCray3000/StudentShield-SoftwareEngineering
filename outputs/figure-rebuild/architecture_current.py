"""Render the current StudentShield deployment and meeting-link architecture.

Only implemented connections are shown. SMS/email arrows represent configured
notification attempts, and Google Meet is an external browser destination.
"""

from __future__ import annotations

from pathlib import Path
from PIL import Image, ImageDraw, ImageFont
import math


OUT = Path(__file__).with_name("figure-08-software-architecture.png")
W, H = 1800, 1500
BLACK = "#000000"
WHITE = "#ffffff"


def font(size: int, bold: bool = False) -> ImageFont.FreeTypeFont:
    name = "timesbd.ttf" if bold else "times.ttf"
    return ImageFont.truetype(str(Path("C:/Windows/Fonts") / name), size)


im = Image.new("RGB", (W, H), WHITE)
draw = ImageDraw.Draw(im)
fb = font(50, True)
fr = font(44)
fs = font(43)
fl = font(43, True)


def center_lines(box: tuple[int, int, int, int], lines: list[str], f: ImageFont.FreeTypeFont,
                 gap: int = 2) -> None:
    x1, y1, x2, y2 = box
    metrics = [draw.textbbox((0, 0), line, font=f) for line in lines]
    heights = [b[3] - b[1] for b in metrics]
    total = sum(heights) + gap * (len(lines) - 1)
    y = (y1 + y2 - total) / 2
    for line, h in zip(lines, heights):
        draw.text(((x1 + x2) / 2, y), line, fill=BLACK, font=f, anchor="mt")
        y += h + gap


def box(bounds: tuple[int, int, int, int], lines: list[str], f=fb) -> None:
    draw.rounded_rectangle(bounds, radius=18, outline=BLACK, width=5, fill=WHITE)
    center_lines(bounds, lines, f)


def arrow(a: tuple[int, int], b: tuple[int, int], width: int = 5,
          head: int = 20) -> None:
    draw.line((a, b), fill=BLACK, width=width)
    theta = math.atan2(b[1] - a[1], b[0] - a[0])
    wing = 0.48
    p1 = (b[0] - head * math.cos(theta - wing), b[1] - head * math.sin(theta - wing))
    p2 = (b[0] - head * math.cos(theta + wing), b[1] - head * math.sin(theta + wing))
    draw.polygon([b, p1, p2], fill=BLACK)


def label(text: str, xy: tuple[int, int], f=fs, pad: int = 7) -> None:
    # A white label panel interrupts its own connector, keeping text readable.
    bounds = draw.textbbox(xy, text, font=f, anchor="mm")
    draw.rectangle((bounds[0] - pad, bounds[1] - pad,
                    bounds[2] + pad, bounds[3] + pad), fill=WHITE)
    draw.text(xy, text, font=f, fill=BLACK, anchor="mm")


# Browser users access the PHP application through HTTPS.
box((100, 80, 710, 240), ["Student browser"])
box((1090, 80, 1700, 240), ["Counselor / admin", "browser"])
arrow((400, 240), (530, 430))
arrow((1400, 240), (890, 430))
label("HTTPS", (454, 318))
label("HTTPS", (1192, 318))

# The web application and relational database share the PHP hosting boundary.
draw.rounded_rectangle((250, 385, 1550, 790), radius=24, outline=BLACK, width=5)
draw.rectangle((1065, 364, 1490, 409), fill=WHITE)
draw.text((1080, 381), "InfinityFree hosting", font=fl, fill=BLACK, anchor="lm")
box((360, 445, 1020, 680), ["StudentShield", "CodeIgniter 4 / PHP"])
box((1160, 480, 1460, 645), ["MySQL /", "MariaDB"], fr)
arrow((1020, 563), (1160, 563))
label("SQL", (1090, 518), fs)

# These are separate external services; gateway success is not asserted.
arrow((535, 680), (430, 935))
arrow((850, 680), (1370, 935))
label("HTTPS JSON + X-API-KEY", (460, 830), fs)
label("alert attempts", (1160, 835), fs)
box((100, 935, 760, 1165), ["Hugging Face Spaces", "FastAPI / PyTorch", "risk + emotion response"], fr)
box((1040, 935, 1700, 1165), ["Configurable SMS / email", "transports"], fr)

# Video is not embedded in the app. The app stores and shows a session URL.
draw.text((100, 1245), "Google Meet link flow (external video)", font=fl, fill=BLACK,
          anchor="lm")
box((100, 1300, 575, 1450), ["Staff creates Meet", "and posts its URL"], fs)
box((665, 1300, 1135, 1450), ["App shows link to", "student and host"], fs)
box((1225, 1300, 1700, 1450), ["Browser opens", "Google Meet"], fs)
arrow((575, 1375), (665, 1375))
arrow((1135, 1375), (1225, 1375))

OUT.parent.mkdir(parents=True, exist_ok=True)
im.save(OUT, optimize=True)
print(OUT)
