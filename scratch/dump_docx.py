import docx

doc = docx.Document(r"d:\laragon\www\student shield\docs\FOLLOW THIS FORMAT.docx")
with open(r"d:\laragon\www\student shield\scratch\full_content.txt", "w", encoding="utf-8") as f:
    for para in doc.paragraphs:
        f.write(para.text + "\n")
