import docx

def read_docx(file_path, output_path):
    doc = docx.Document(file_path)
    fullText = []
    for para in doc.paragraphs:
        fullText.append(para.text)
    with open(output_path, 'w', encoding='utf-8') as f:
        f.write('\n'.join(fullText))

if __name__ == '__main__':
    doc_path = r"d:\laragon\www\student shield\docs\FOLLOW THIS FORMAT.docx"
    out_path = r"d:\laragon\www\student shield\scratch\docx_content.txt"
    read_docx(doc_path, out_path)
