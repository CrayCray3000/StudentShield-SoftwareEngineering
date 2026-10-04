from pathlib import Path
from pypdf import PdfReader
import pypdfium2 as pdfium
from PIL import Image,ImageDraw
import json,re
OUT=Path(__file__).parent
pdf=OUT/'revised-review.pdf'
r=PdfReader(pdf)
texts=[p.extract_text() for p in r.pages]
(OUT/'revised-readable.txt').write_text('\n\n'.join(f'PAGE {i+1}\n'+t for i,t in enumerate(texts)),encoding='utf-8')
print('pages',len(texts))
print('\n'.join(f'{i+1}: '+re.sub(r'\s+',' ',t)[:170]+' ... '+re.sub(r'\s+',' ',t)[-45:] for i,t in enumerate(texts)))
d=pdfium.PdfDocument(pdf)
for start in range(0,len(d),6):
 sheet=Image.new('RGB',(1530,1320),'#aaa')
 for j in range(start,min(start+6,len(d))):
  im=d[j].render(scale=.82).to_pil();im.thumbnail((500,647))
  x=(j-start)%3*510;y=(j-start)//3*660
  sheet.paste(im,(x,y));ImageDraw.Draw(sheet).text((x+4,y+645),str(j+1),fill='black')
 sheet.save(OUT/f'review-{start//6+1:02}.png')
