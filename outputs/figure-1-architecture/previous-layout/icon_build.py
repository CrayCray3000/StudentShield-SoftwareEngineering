"""Editable, monochrome deployment architecture with native service icons."""
from pathlib import Path
import math
import xml.etree.ElementTree as E
from PIL import Image, ImageDraw, ImageFont

OUT=Path(__file__).parent
W,H=1500,1210
nodes=[]; labels=[]; edges=[]; primitives=[]
def node(key,x,y,w,h,visible=False):
    nodes.append(dict(id=key,x=x,y=y,w=w,h=h,visible=visible))
def label(key,x,y,value,size=28,bold=False,parent=None):
    labels.append(dict(id=key,x=x,y=y,value=value,size=size,bold=bold,parent=parent))
def primitive(owner,kind,coords):
    primitives.append(dict(id=f'icon-{len(primitives)}',owner=owner,kind=kind,coords=coords))
def icon(key,cx,cy,kind):
    node(key,cx-32,cy-32,64,64)
    def box(k,x,y,w,h): primitive(key,k,(cx+x,cy+y,w,h))
    def path(points): primitive(key,'line',[(cx+x,cy+y) for x,y in points])
    if kind=='person':
        box('ellipse',-13,-30,26,26);box('round',-26,3,52,29)
    elif kind=='browser':
        box('round',-32,-28,64,56);path([(-32,-12),(32,-12)])
        for x in [-23,-14,-5]: box('ellipse',x,-22,3,3)
        path([(-12,9),(-3,18),(15,-1)])
    elif kind=='server':
        for y in [-27,5]:
            box('round',-32,y,64,23);box('ellipse',-23,y+8,6,6)
            path([(-8,y+12),(22,y+12)])
    elif kind=='database':
        box('ellipse',-30,-30,60,20)
        path([(-30,-20),(-30,22)]);path([(30,-20),(30,22)])
        for y in [0,22]:
            path([(30*math.cos(a),y+10*math.sin(a)) for a in [i*math.pi/24 for i in range(25)]])
    elif kind=='chip':
        box('round',-23,-23,46,46);box('rect',-12,-12,24,24)
        for p in [-15,0,15]:
            path([(p,-32),(p,-23)]);path([(p,23),(p,32)])
            path([(-32,p),(-23,p)]);path([(23,p),(32,p)])
    elif kind=='mail':
        box('round',-32,-23,64,46)
        path([(-30,-20),(0,2),(30,-20)])
        path([(-30,20),(-10,0)]);path([(30,20),(10,0)])
    elif kind=='video':
        box('round',-32,-22,43,44);path([(13,-10),(31,-22),(31,22),(13,10),(13,-10)])
def edge(key,src,dst,pts,both=False):
    edges.append(dict(id=key,src=src,dst=dst,pts=pts,both=both))

# Separate deployment boundaries and aligned external service groups.
node('web-host',560,50,900,470,True)
label('web-host-name',1010,90,'InfinityFree web hosting',34,True,'web-host')
node('app-box',620,140,380,340,True)
node('db-box',1090,140,290,340,True)
node('ml-host',50,660,420,300,True)
label('ml-host-name',260,695,'Hugging Face Spaces',32,True,'ml-host')
node('calls',540,660,420,300,True)
label('calls-title',750,695,'Online counseling',32,True,'calls')
node('delivery',1030,660,420,300,True)
label('delivery-title',1240,695,'Notification services',32,True,'delivery')

icon('student',105,110,'person')
label('student-name',105,175,'Student',31,True)
icon('staff',105,340,'person')
label('staff-name',105,405,'Staff',31,True)
label('staff-role-1',105,441,'Counselor /',26)
label('staff-role-2',105,476,'Super Administrator',25)
icon('access',365,260,'browser')
label('access-name',365,326,'Cloudflare',31,True)
label('access-note',365,362,'HTTPS web access',26)
icon('app',810,215,'server')
label('app-name',810,290,'StudentShield',34,True,'app-box')
label('app-framework',810,332,'CodeIgniter 4 / PHP',28,False,'app-box')
label('app-modules',810,377,'Vents, reports and sessions',26,False,'app-box')
label('app-fallback',810,419,'Local screening fallback',26,False,'app-box')
icon('database',1235,215,'database')
label('database-name',1235,290,'MySQL / MariaDB',30,True,'db-box')
label('database-note-1',1235,334,'Profiles and submissions',25,False,'db-box')
label('database-note-2',1235,375,'Risk history',26,False,'db-box')
label('database-note-3',1235,416,'Meeting records',26,False,'db-box')
icon('ml',260,770,'chip')
label('ml-name',260,834,'Screening service',31,True,'ml-host')
label('ml-framework',260,875,'FastAPI / PyTorch',27,False,'ml-host')
label('ml-models',260,917,'mDeBERTa / GoEmotions',27,False,'ml-host')
icon('meet',750,770,'video')
label('meet-name',750,834,'Google Meet',31,True,'calls')
label('meet-note',750,875,'Staff-published session link',27,False,'calls')
label('meet-browser',750,917,'Opens in the browser',27,False,'calls')
icon('transport',1240,770,'mail')
label('transport-name',1240,834,'SMS gateway / SMTP',29,True,'delivery')
label('transport-note',1240,875,'Configured transports',27,False,'delivery')
label('transport-conditional',1240,917,'Conditional notifications',27,False,'delivery')
icon('recipients',1240,1070,'person')
label('recipients-name',1240,1135,'Counselors and eligible',28,True)
label('recipients-detail',1240,1174,'parents / guardians',28)

edge('student-access','student','access',[(137,110),(365,110),(365,228)])
edge('staff-access','staff','access',[(137,340),(230,340),(230,272),(333,272)])
edge('web-access','access','app-box',[(397,260),(620,260)],True)
label('https',510,230,'HTTPS',26)
edge('database-connection','app-box','db-box',[(1000,260),(1090,260)],True)
label('sql',1045,230,'SQL',26)
edge('screening','app-box','ml',[(620,420),(500,420),(500,770),(292,770)],True)
label('screening-label',388,737,'Screening API',26)
edge('join-meet','app-box','meet',[(810,480),(810,580),(990,580),(990,770),(782,770)])
label('join-label',879,737,'Join redirect',26)
edge('notifications','app-box','transport',[(1000,420),(1015,420),(1015,600),(1430,600),(1430,770),(1272,770)])
label('notifications-label',1350,737,'Alert attempts',26)
edge('delivery-to-people','delivery','recipients',[(1240,960),(1240,1038)])
label('delivery-label',1360,1000,'Eligible alerts',26)

def font(size,bold=False,scale=1):
    return ImageFont.truetype('C:/Windows/Fonts/'+('timesbd.ttf' if bold else 'times.ttf'),int(size*scale))
measure=ImageDraw.Draw(Image.new('RGB',(W,H)))
boxes=[]
for t in labels:
    b=measure.textbbox((t['x'],t['y']),t['value'],font=font(t['size'],t['bold']),anchor='mm')
    assert 0<=b[0]<b[2]<=W and 0<=b[1]<b[3]<=H,t['id']
    boxes.append((t['id'],b))
for i,(name,b) in enumerate(boxes):
    for other,c in boxes[i+1:]:
        assert not (b[0]<c[2] and b[2]>c[0] and b[1]<c[3] and b[3]>c[1]),(name,other)
    for e in edges:
        for (x1,y1),(x2,y2) in zip(e['pts'],e['pts'][1:]):
            hit=(x1==x2 and b[0]-2<x1<b[2]+2 and min(y1,y2)<b[3]+2 and max(y1,y2)>b[1]-2)
            hit=hit or (y1==y2 and b[1]-2<y1<b[3]+2 and min(x1,x2)<b[2]+2 and max(x1,x2)>b[0]-2)
            assert not hit,(name,e['id'])
    for n in nodes:
        if not n['visible']: continue
        left,top,right,bottom=n['x'],n['y'],n['x']+n['w'],n['y']+n['h']
        for vx in [left,right]:
            assert not (b[0]<vx<b[2] and b[1]<bottom and b[3]>top),(name,n['id'],'vertical border')
        for hy in [top,bottom]:
            assert not (b[1]<hy<b[3] and b[0]<right and b[2]>left),(name,n['id'],'horizontal border')

svg=E.Element('svg',xmlns='http://www.w3.org/2000/svg',viewBox=f'0 0 {W} {H}',width=str(W),height=str(H))
E.SubElement(svg,'title').text='Figure 1. StudentShield software architecture'
E.SubElement(svg,'rect',width=str(W),height=str(H),fill='white')
defs=E.SubElement(svg,'defs')
marker=E.SubElement(defs,'marker',id='arrow',viewBox='0 0 12 12',refX='11',refY='6',markerWidth='5',markerHeight='5',orient='auto-start-reverse')
E.SubElement(marker,'path',d='M0 0 L12 6 L0 12 Z',fill='black')
im=Image.new('RGB',(W*2,H*2),'white');d=ImageDraw.Draw(im)
mx=E.Element('mxfile',host='app.diagrams.net',type='device')
dia=E.SubElement(mx,'diagram',id='studentshield-architecture',name='Figure 1 - Software Architecture')
graph=E.SubElement(dia,'mxGraphModel',grid='1',gridSize='10',page='1',pageScale='1',pageWidth=str(W),pageHeight=str(H),connect='1',arrows='1')
root=E.SubElement(graph,'root');E.SubElement(root,'mxCell',id='0');E.SubElement(root,'mxCell',id='1',parent='0')
lookup={n['id']:n for n in nodes}
parents={'app-box':'web-host','db-box':'web-host','app':'app-box',
         'database':'db-box','ml':'ml-host','meet':'calls','transport':'delivery'}
def cell_rect(key,x,y,w,h,style,parent='1',value=''):
    c=E.SubElement(root,'mxCell',id=key,value=value,vertex='1',parent=parent,style=style)
    E.SubElement(c,'mxGeometry',{'x':str(x),'y':str(y),'width':str(w),'height':str(h),'as':'geometry'})
    return c
for n in nodes:
    x,y,w,h=n['x'],n['y'],n['w'],n['h']
    style='rounded=1;arcSize=5;fillColor=#ffffff;strokeColor=#000000;strokeWidth=2;' if n['visible'] else 'fillColor=none;strokeColor=none;'
    parent=parents.get(n['id'],'1');owner=lookup.get(parent,{'x':0,'y':0})
    cell_rect(n['id'],x-owner['x'],y-owner['y'],w,h,style,parent)
    if n['visible']:
        E.SubElement(svg,'rect',x=str(x),y=str(y),width=str(w),height=str(h),rx='12',fill='white',stroke='black',**{'stroke-width':'2'})
        d.rounded_rectangle((x*2,y*2,(x+w)*2,(y+h)*2),radius=24,outline='black',width=4)
for e in edges:
    pts=e['pts']
    attrs={'points':' '.join(f'{x},{y}' for x,y in pts),'fill':'none','stroke':'black','stroke-width':'2','stroke-linejoin':'round','marker-end':'url(#arrow)'}
    if e['both']: attrs['marker-start']='url(#arrow)'
    E.SubElement(svg,'polyline',attrs)
    scaled=[(x*2,y*2) for x,y in pts];d.line(scaled,fill='black',width=4)
    def head(a,b):
        angle=math.atan2(b[1]-a[1],b[0]-a[0]);length=23
        d.polygon([b]+[(b[0]-length*math.cos(angle+s*.45),b[1]-length*math.sin(angle+s*.45)) for s in [-1,1]],fill='black')
    head(scaled[-2],scaled[-1])
    if e['both']:head(scaled[1],scaled[0])
    s,t=lookup[e['src']],lookup[e['dst']]
    ex,ey=(pts[0][0]-s['x'])/s['w'],(pts[0][1]-s['y'])/s['h']
    ix,iy=(pts[-1][0]-t['x'])/t['w'],(pts[-1][1]-t['y'])/t['h']
    c=E.SubElement(root,'mxCell',id=e['id'],edge='1',parent='1',source=e['src'],target=e['dst'],style=f'edgeStyle=none;rounded=1;strokeColor=#000000;strokeWidth=2;endArrow=block;endFill=1;startArrow={"block" if e["both"] else "none"};startFill=1;exitX={ex};exitY={ey};entryX={ix};entryY={iy};')
    geo=E.SubElement(c,'mxGeometry',{'relative':'1','as':'geometry'})
    arr=E.SubElement(geo,'Array',{'as':'points'})
    for x,y in pts[1:-1]:E.SubElement(arr,'mxPoint',x=str(x),y=str(y))
for p in primitives:
    owner=lookup[p['owner']];ox,oy=owner['x'],owner['y']
    if p['kind']=='line':
        pts=p['coords'];E.SubElement(svg,'polyline',points=' '.join(f'{x},{y}' for x,y in pts),fill='none',stroke='black',**{'stroke-width':'2.5','stroke-linejoin':'round'})
        d.line([(x*2,y*2) for x,y in pts],fill='black',width=5)
        c=E.SubElement(root,'mxCell',id=p['id'],edge='1',parent=p['owner'],style='edgeStyle=none;endArrow=none;startArrow=none;strokeWidth=2.5;strokeColor=#000000;')
        geo=E.SubElement(c,'mxGeometry',{'relative':'1','as':'geometry'})
        for pt,name in [(pts[0],'sourcePoint'),(pts[-1],'targetPoint')]:E.SubElement(geo,'mxPoint',{'x':str(pt[0]-ox),'y':str(pt[1]-oy),'as':name})
        arr=E.SubElement(geo,'Array',{'as':'points'})
        for x,y in pts[1:-1]:E.SubElement(arr,'mxPoint',x=str(x-ox),y=str(y-oy))
    else:
        x,y,w,h=p['coords'];kind=p['kind'];bounds=(x*2,y*2,(x+w)*2,(y+h)*2)
        if kind=='ellipse':
            E.SubElement(svg,'ellipse',cx=str(x+w/2),cy=str(y+h/2),rx=str(w/2),ry=str(h/2),fill='white',stroke='black',**{'stroke-width':'2.5'})
            d.ellipse(bounds,fill='white',outline='black',width=5)
        else:
            E.SubElement(svg,'rect',x=str(x),y=str(y),width=str(w),height=str(h),rx='4' if kind=='round' else '0',fill='white',stroke='black',**{'stroke-width':'2.5'})
            d.rounded_rectangle(bounds,radius=8 if kind=='round' else 0,fill='white',outline='black',width=5)
        style=f'shape={"ellipse" if kind=="ellipse" else "rectangle"};rounded={1 if kind=="round" else 0};arcSize=15;fillColor=#ffffff;strokeColor=#000000;strokeWidth=2.5;'
        cell_rect(p['id'],x-ox,y-oy,w,h,style,p['owner'])
for t in labels:
    E.SubElement(svg,'text',x=str(t['x']),y=str(t['y']),fill='black',**{'text-anchor':'middle','dominant-baseline':'middle','font-family':'Times New Roman, Times, serif','font-size':str(t['size']),'font-weight':'bold' if t['bold'] else 'normal'}).text=t['value']
    d.text((t['x']*2,t['y']*2),t['value'],font=font(t['size'],t['bold'],2),fill='black',anchor='mm')
    width=font(t['size'],t['bold']).getlength(t['value'])+20
    parent=t['parent'] or '1';own=lookup.get(parent,{'x':0,'y':0})
    cell_rect(t['id'],t['x']-width/2-own['x'],t['y']-t['size']*.7-own['y'],width,t['size']*1.4,
              f'text;html=0;align=center;verticalAlign=middle;whiteSpace=wrap;fontFamily=Times New Roman;fontSize={t["size"]};fontStyle={int(t["bold"])};fontColor=#000000;strokeColor=none;fillColor=none;spacing=0;',parent,t['value'])
E.indent(mx)
E.ElementTree(mx).write(OUT/'StudentShield-Architecture.drawio',encoding='utf-8',xml_declaration=True)
svgtext=E.tostring(svg,encoding='unicode')
(OUT/'StudentShield-Architecture.svg').write_text(svgtext,encoding='utf-8')
im.save(OUT/'StudentShield-Architecture.png',dpi=(300,300))
im.resize((1125,908)).save(OUT/'preview.png')
html=(OUT/'index.html').read_text(encoding='utf-8')
start=html.index('<svg');end=html.index('</svg>',start)+6
html=html[:start]+svgtext+html[end:]
html=html.replace('max-width:1100px','max-width:1400px')
html=html.replace('@page{size:A4;margin:18mm}', '@page{size:A4 landscape;margin:15mm}')
html=html.replace('.diagram svg{min-width:0;width:100%}', '.diagram svg{min-width:0;width:100%;max-height:155mm}')
html=html.replace('max-height:155mm','max-height:165mm')
html=html.replace('.explanation{font-size:12pt;line-height:1.5}', '.explanation{display:none}')
(OUT/'index.html').write_text(html,encoding='utf-8')
print('Updated draw.io, SVG, PNG, HTML. Text overlap and connector-label checks passed.')
