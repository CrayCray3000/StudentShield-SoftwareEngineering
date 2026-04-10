import sys
from pypdf import PdfReader

try:
    reader = PdfReader("docs/Thesis_Guides/Capstone Project I Format Documentation.docx (1).pdf")
    text = ""
    for i, page in enumerate(reader.pages):
        text += f"\n--- Page {i+1} ---\n"
        text += page.extract_text()
    
    with open("pdf_content2.txt", "w", encoding="utf-8") as f:
        f.write(text)
    print("PDF extracted to pdf_content2.txt")
except Exception as e:
    print(f"Error reading PDF: {e}")
