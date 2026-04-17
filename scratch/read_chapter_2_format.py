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

print("\n--- CHAPTER 2 SECTION ---")
found = False
found_chapter2 = False
for i, line in enumerate(lines):
    text = line.upper()
    if 'CHAPTER II' in text or ('CHAPTER' in text and '2' in text):
        found_chapter2 = True
    if found_chapter2:
        if 'CHAPTER III' in text or ('CHAPTER' in text and '3' in text):
            break
        print(line)

if not found:
    print("Could not find Chapter 2 section.")
