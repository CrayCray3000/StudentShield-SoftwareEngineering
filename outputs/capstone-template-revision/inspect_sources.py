from pathlib import Path
from zipfile import ZipFile
from PIL import Image, ImageOps, ImageDraw
import json
from lxml import etree

SOURCE = Path(r'C:\Users\drvon\AppData\Local\Temp\browser-use\exports\StudentShield Capstone-6396cf8d-1256-4289-9048-7cfb362f8ffe.docx')
OUT = Path(__file__).parent
NS = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'a': 'http://schemas.openxmlformats.org/drawingml/2006/main', 'r': 'http://schemas.openxmlformats.org/officeDocument/2006/relationships'}
with ZipFile(SOURCE) as z:
    root = etree.fromstring(z.read('word/document.xml'))
    paragraphs = root.findall('.//w:body//w:p', NS)
    data = [{'index': i, 'text': ''.join(p.itertext()) if False else ''.join(p.xpath('.//w:t/text()', namespaces=NS)), 'xml': etree.tostring(p).decode()} for i, p in enumerate(paragraphs)]
    (OUT / 'source_paragraphs.json').write_text(json.dumps(data, ensure_ascii=False), encoding='utf-8')
    media = []
    for name in z.namelist():
        if name.startswith('word/media/'):
            dest = OUT / Path(name).name
            dest.write_bytes(z.read(name))
            media.append(dest)
    tiles = []
    for path in sorted(media):
        im = Image.open(path).convert('RGB')
        tile = Image.new('RGB', (1200, 960), 'white')
        ImageDraw.Draw(tile).text((20, 10), f'{path.name} — {im.width} x {im.height}', fill='black')
        preview = ImageOps.contain(im, (1160, 910))
        tile.paste(preview, ((1200-preview.width)//2, 40))
        tiles.append(tile)
    for i in range(0, len(tiles), 2):
        sheet = Image.new('RGB', (2400, 960), '#dddddd')
        for j, tile in enumerate(tiles[i:i+2]):
            sheet.paste(tile, (j*1200, 0))
        sheet.save(OUT / f'diagrams-{i//2+1}.png')
    print(json.dumps({'media': [str(p) for p in media], 'paragraphs': len(data)}))
