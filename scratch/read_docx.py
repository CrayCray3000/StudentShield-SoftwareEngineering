import sys
import io
import docx

# Set stdout to use utf-8
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

def get_docx_text(filename):
    doc = docx.Document(filename)
    fullText = []
    for para in doc.paragraphs:
        fullText.append(para.text)
    return fullText

lines = get_docx_text('docs/FOLLOW THIS FORMAT.docx')

# Search for 'Review of Related Literature' and print 50 lines around it
print("\n--- RRL SECTION ---")
for i, line in enumerate(lines):
    if "1.5 Review of Related Literature" in line or "Review of Related Literature" in line:
        start = i
        end = i + 100
        for j in range(start, min(end, len(lines))):
            print(f"{j}: {lines[j]}")
        break

# Search for 'Review of Related Studies' and print 50 lines around it
print("\n--- RRS SECTION ---")
for i, line in enumerate(lines):
    if "1.6 Review of Related Studies" in line or "Review of Related Studies" in line:
        start = i
        end = i + 100
        for j in range(start, min(end, len(lines))):
            print(f"{j}: {lines[j]}")
        break
