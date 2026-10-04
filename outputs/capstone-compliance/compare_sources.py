from pathlib import Path
from docx import Document
from pypdf import PdfReader
import re,json
out=Path(__file__).parent
root=out.resolve().parents[1]
src=Document(r'C:\Users\drvon\AppData\Local\Temp\browser-use\exports\StudentShield Capstone-6396cf8d-1256-4289-9048-7cfb362f8ffe.docx')
norm=lambda s: re.sub(r'[^a-z0-9]','',s.lower())
pages=[p.extract_text() for p in PdfReader(root/'StudentShield Capstone.pdf').pages]
pdf=norm(''.join(re.sub(r'National\s+Teachers\s+College\s+\d+','',p) for p in pages))
missing=[]
for i,p in enumerate(src.paragraphs):
 if len(p.text)>70 and norm(p.text) not in pdf: missing.append((i,p.text))
(out/'source-differences.json').write_text(json.dumps(missing,ensure_ascii=False,indent=2),encoding='utf-8')
print(json.dumps(missing,ensure_ascii=True,indent=2))
