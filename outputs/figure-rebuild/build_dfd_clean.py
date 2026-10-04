"""Current logical DFDs. Repeated entities/stores retain the same identity."""
from pathlib import Path
from PIL import Image, ImageDraw, ImageFont
import math
import xml.etree.ElementTree as ET
ROOT=Path(__file__).parent
S=3
class Diagram:
    def __init__(self,w,h):
        self.w=w;self.h=h
        self.im=Image.new('RGB',(w*S,h*S),'white'); self.d=ImageDraw.Draw(self.im)
        self.mx=ET.Element('mxGraphModel',page='1',pageWidth=str(w),pageHeight=str(h)); self.root=ET.SubElement(self.mx,'root')
        ET.SubElement(self.root,'mxCell',id='0');ET.SubElement(self.root,'mxCell',id='1',parent='0');self.n=1
    def line(self,pts,arrow=False):
        self.d.line([(x*S,y*S) for x,y in pts],fill='black',width=3,joint='curve')
        if arrow:
            x,y=pts[-1];a=math.atan2(y-pts[-2][1],x-pts[-2][0]);self.d.polygon([(x*S,y*S),((x-9*math.cos(a-.4))*S,(y-9*math.sin(a-.4))*S),((x-9*math.cos(a+.4))*S,(y-9*math.sin(a+.4))*S)],fill='black')
        self.n+=1;c=ET.SubElement(self.root,'mxCell',id=str(self.n),parent='1',edge='1',style='endArrow='+('block' if arrow else 'none')+';strokeColor=#000000;strokeWidth=1;')
        g=ET.SubElement(c,'mxGeometry',relative='1',**{'as':'geometry'});ET.SubElement(g,'mxPoint',x=str(pts[0][0]),y=str(pts[0][1]),**{'as':'sourcePoint'});ET.SubElement(g,'mxPoint',x=str(pts[-1][0]),y=str(pts[-1][1]),**{'as':'targetPoint'})
        if len(pts)>2:
            ar=ET.SubElement(g,'Array',**{'as':'points'})
            for x,y in pts[1:-1]:ET.SubElement(ar,'mxPoint',x=str(x),y=str(y))
    def text(self,x,y,s,size=24,bold=False):
        font=ImageFont.truetype('C:/Windows/Fonts/timesbd.ttf' if bold else 'C:/Windows/Fonts/times.ttf',size*S)
        self.d.multiline_text((x*S,y*S),s,font=font,fill='black',anchor='mm',align='center',spacing=4*S)
        self.n+=1;c=ET.SubElement(self.root,'mxCell',id=str(self.n),parent='1',vertex='1',value=s,style=f'text;html=0;align=center;verticalAlign=middle;whiteSpace=wrap;fontFamily=Times New Roman;fontSize={size};fontStyle={1 if bold else 0};fontColor=#000000;')
        ET.SubElement(c,'mxGeometry',x=str(x-145),y=str(y-(s.count(chr(10))+1)*(size+4)/2),width='290',height=str((s.count(chr(10))+1)*(size+4)),**{'as':'geometry'})
    def rect(self,x,y,w,h,round=False):
        box=(x*S,y*S,(x+w)*S,(y+h)*S)
        if round:self.d.rounded_rectangle(box,14*S,fill='white',outline='black',width=3)
        else:self.d.rectangle(box,fill='white',outline='black',width=3)
        self.n+=1;c=ET.SubElement(self.root,'mxCell',id=str(self.n),parent='1',vertex='1',style=f'rounded={int(round)};fillColor=#ffffff;strokeColor=#000000;strokeWidth=1;')
        ET.SubElement(c,'mxGeometry',x=str(x),y=str(y),width=str(w),height=str(h),**{'as':'geometry'})
    def entity(self,y,label):self.rect(10,y,215,78);self.text(117,y+39,label,23,True)
    def store(self,y,label):
        self.line([(985,y),(1200,y)]);self.line([(985,y+74),(1200,y+74)]);self.text(1092,y+37,label,25)
    def process(self,y,num,name,h=305):
        self.rect(475,y,265,h,True);self.line([(475,y+46),(740,y+46)]);self.text(607,y+23,num,27,True);self.text(607,y+46+(h-46)/2,name,30,True)
    def left_in(self,y,label):self.line([(225,y),(475,y)],True);self.text(350,y-28,label,25)
    def left_out(self,y,label):self.line([(475,y),(225,y)],True);self.text(350,y+27,label,25)
    def right_in(self,y,label):self.line([(985,y),(740,y)],True);self.text(862,y-25,label,25)
    def right_out(self,y,label):self.line([(740,y),(985,y)],True);self.text(862,y+25,label,25)
    def save(self,name):
        self.im.save(ROOT/(name+'.png'),optimize=True)
        mf=ET.Element('mxfile',host='app.diagrams.net');di=ET.SubElement(mf,'diagram',name=name,id=name);di.append(self.mx);ET.ElementTree(mf).write(ROOT/(name+'.drawio'),encoding='utf-8',xml_declaration=True)

def context():
    f=Diagram(1220,660)
    f.rect(460,150,300,240,True);f.line([(460,202),(760,202)]);f.text(610,176,'0',30,True);f.text(610,300,'StudentShield\nWeb Application',31,True)
    f.rect(10,202,220,100);f.text(120,252,'Student',29,True)
    f.rect(990,202,220,100);f.text(1100,252,'Counselor /\nSuper Administrator',22,True)
    f.line([(230,225),(460,225)],True);f.text(344,158,'Account / submission\ndata; session requests',23)
    f.line([(460,282),(230,282)],True);f.text(344,346,'Access / submission\nresults; session\nstatus and link',23)
    f.line([(990,225),(760,225)],True);f.text(875,147,'Credentials / decisions;\ncase and session data;\nqueries',23)
    f.line([(760,282),(990,282)],True);f.text(875,346,'Records and results;\nverification and\ntriage notices',23)
    f.rect(80,548,270,80);f.text(215,588,'ML screening service',26,True)
    f.rect(870,548,300,80);f.text(1020,588,'Parent / Guardian',27,True)
    f.line([(520,390),(520,508),(280,508),(280,548)],True);f.text(395,477,'Current vent, student ID,\n14-day risk history',23)
    f.line([(140,548),(140,438),(460,438),(460,375)],True);f.text(285,412,'Risk / trajectory assessment',23)
    f.line([(710,390),(710,508),(1020,508),(1020,548)],True);f.text(883,467,'Eligible notices\nand receipts',23)
    f.save('dfd-level-0-clean')

def level1():
    f=Diagram(1220,1620)
    # Each row repeats the same entity/store names to keep long routes off the page.
    f.process(35,'1.0','Manage accounts\nand access',285)
    f.entity(20,'Student');f.left_in(43,'Registration / login');f.left_out(91,'Access / account result')
    f.entity(215,'Counselor /\nSuper Administrator');f.left_in(234,'Credentials, OTP,\naccount decisions');f.left_out(287,'Results / verification\nmessages')
    f.store(30,'D1  Account and\nprofile records');f.right_in(48,'Account records');f.right_out(95,'Account changes')
    f.store(135,'D2  Student\ncase records');f.right_out(170,'Report flag updates')
    f.store(243,'D3  Activity records');f.right_out(279,'Audit / OTP records')

    # Vent flow. Notifications are part of this process, as in the actual controller.
    y=415;f.process(y,'2.0','Assess and\nrecord vents',330)
    f.entity(y-5,'Student');f.left_in(y+15,'Vent, moods,\nprivacy choice');f.left_out(y+62,'Submission result')
    f.entity(y+121,'ML screening\nservice');f.line([(475,y+143),(225,y+143)],True);f.text(350,y+116,'Vent, ID, risk history',25);f.line([(225,y+191),(475,y+191)],True);f.text(350,y+218,'Risk / trajectory result',25)
    f.entity(y+262,'Counselor / eligible\nparent or guardian');f.left_out(y+297,'Wellness / triage notice')
    f.store(y-5,'D2  Student\ncase records');f.right_in(y+14,'Recent risk history');f.right_out(y+63,'Vent and risk record')
    f.store(y+125,'D1  Account and\nprofile records');f.right_in(y+160,'Recipient contacts')
    f.store(y+260,'D3  Activity records');f.right_in(y+276,'Recent alert attempts');f.right_out(y+328,'Vent alert attempts')

    y=830;f.process(y,'3.0','Handle incident\nreports',330)
    f.entity(y-5,'Student /\nreporting staff');f.left_in(y+14,'Report and evidence');f.left_out(y+62,'Submission result')
    f.entity(y+125,'Counselor /\nSuper Administrator');f.left_in(y+144,'Review decision');f.left_out(y+192,'Review result /\ntriage notice')
    f.entity(y+265,'Parent / Guardian');f.left_out(y+302,'Eligible notices /\nsubmission receipt')
    f.store(y-5,'D2  Student\ncase records');f.right_in(y+14,'Incident records');f.right_out(y+62,'Incident updates')
    f.store(y+126,'D1  Account and\nprofile records');f.right_in(y+144,'Identity / contact data');f.right_out(y+193,'Account lock status')
    f.store(y+265,'D3  Activity records');f.right_out(y+302,'Incident audit events')

    y=1245;f.process(y,'4.0','Review cases\nand manage\nsessions',330)
    f.entity(y+5,'Student');f.left_in(y+25,'Session request /\ncancellation / access');f.left_out(y+77,'Session status / link')
    f.entity(y+242,'Counselor /\nSuper Administrator');f.left_in(y+260,'Queries / case changes;\nsession details');f.left_out(y+317,'Records / summaries;\nupdate results')
    f.store(y-5,'D1  Account and\nprofile records');f.right_in(y+32,'Profile records')
    f.store(y+126,'D2  Student\ncase records');f.right_in(y+144,'Vents, reports,\nnotes and sessions');f.right_out(y+194,'Case / session changes')
    f.store(y+265,'D3  Activity records');f.right_in(y+278,'Audit / alert records');f.right_out(y+328,'Selected audit events')

    f.save('dfd-level-1-clean')

if __name__=='__main__':context();level1()





