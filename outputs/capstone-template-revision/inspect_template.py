from pathlib import Path
from zipfile import ZipFile
from lxml import etree
import pypdfium2 as pdfium

OUT = Path(__file__).parent
ROOT = Path(r'C:\Users\drvon\AppData\Local\Temp\browser-use\exports')
DOCX = ROOT / 'Capstone Documentation Template.docx-b6babcd3-32ca-473b-b40a-61bb822344eb.docx'
PDF = ROOT / 'Capstone Documentation Template.docx-465ca0c1-5893-44fa-97fe-7fe5f20e6f93.pdf'
NS = {'w':'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
with ZipFile(DOCX) as z:
    for name in z.namelist():
        if name.startswith('word/media/'):
            (OUT / ('template-' + Path(name).name)).write_bytes(z.read(name))
    for name in ['word/header1.xml','word/footer1.xml','word/styles.xml']:
        if name in z.namelist():
            root = etree.fromstring(z.read(name))
            if 'styles' not in name:
                print(name, etree.tostring(root).decode())
            else:
                print('default styles', etree.tostring(root.find('w:docDefaults',NS)).decode())
doc = pdfium.PdfDocument(PDF)
for i in [0,1,4,5,6]:
    if i<len(doc):
        doc[i].render(scale=1.25).to_pil().save(OUT / f'template-page-{i+1}.png')
print('PDF pages',len(doc))
