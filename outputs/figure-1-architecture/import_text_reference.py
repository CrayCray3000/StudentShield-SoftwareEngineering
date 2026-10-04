from pathlib import Path
from html.parser import HTMLParser
import json
import xml.etree.ElementTree as E

out=Path(__file__).parent
class ExportParser(HTMLParser):
    def handle_starttag(self,tag,attrs):
        data=dict(attrs).get('data-mxgraph')
        if data: self.graph=json.loads(data)
p=ExportParser()
p.feed(Path('C:/Users/drvon/Downloads/Untitled Diagram.drawio.html').read_text(encoding='utf-8'))
root=E.fromstring(p.graph['xml'])
cells={c.get('id'):c for c in root.iter('mxCell')}
def origin(c):
    g=c.find('mxGeometry')
    x=float(g.get('x',0)) if g is not None else 0
    y=float(g.get('y',0)) if g is not None else 0
    parent=cells.get(c.get('parent'))
    if parent is not None:
        px,py=origin(parent);x+=px;y+=py
    return x,y
texts=[]
for c in cells.values():
    if not c.get('value'):continue
    style=dict(pair.split('=',1) for pair in c.get('style','').split(';') if '=' in pair)
    g=c.find('mxGeometry');x,y=origin(c)
    w,h=float(g.get('width')),float(g.get('height'))
    texts.append(dict(value=c.get('value'),x=x+w/2,y=y+h/2,w=w,h=h,size=float(style['fontSize']),bold=bool(int(style.get('fontStyle','0'))&1),style=c.get('style')))
(out/'text-reference.json').write_text(json.dumps(texts,indent=2),encoding='utf-8')
(out/'User-reference.drawio').write_text(p.graph['xml'],encoding='utf-8')
print(json.dumps(texts,indent=2))
