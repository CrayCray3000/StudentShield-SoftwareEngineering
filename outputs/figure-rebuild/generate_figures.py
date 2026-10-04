"""Generate monochrome StudentShield diagrams from the checked-in implementation.

The diagrams are documentation illustrations, not claims of deployed database
state, successful message delivery, or completed evaluation.
"""

from __future__ import annotations

import math
from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


ROOT = Path(__file__).resolve().parent
S = 3
FONT = "C:/Windows/Fonts/times.ttf"
BOLD = "C:/Windows/Fonts/timesbd.ttf"


class Figure:
    def __init__(self, width: int, height: int):
        self.w, self.h = width, height
        self.im = Image.new("RGB", (width * S, height * S), "white")
        self.d = ImageDraw.Draw(self.im)

    def font(self, size: int, bold: bool = False):
        return ImageFont.truetype(BOLD if bold else FONT, size * S)

    def line(self, points, width: int = 2):
        self.d.line([(int(x * S), int(y * S)) for x, y in points], fill="black", width=width * S, joint="curve")

    def rect(self, xy, radius: int = 0, width: int = 2):
        xy = tuple(int(v * S) for v in xy)
        if radius:
            self.d.rounded_rectangle(xy, radius * S, outline="black", width=width * S, fill="white")
        else:
            self.d.rectangle(xy, outline="black", width=width * S, fill="white")

    def ellipse(self, xy, width: int = 2):
        self.d.ellipse(tuple(int(v * S) for v in xy), outline="black", width=width * S, fill="white")

    def circle(self, x, y, r=9, filled=False):
        self.d.ellipse(((x-r)*S,(y-r)*S,(x+r)*S,(y+r)*S), fill="black" if filled else "white", outline="black", width=2*S)

    def arrow(self, points, width: int = 2, head: int = 11):
        self.line(points, width)
        x1, y1 = points[-2]
        x2, y2 = points[-1]
        a = math.atan2(y2-y1, x2-x1)
        a1, a2 = a + math.pi * .82, a - math.pi * .82
        poly = [(x2,y2), (x2+head*math.cos(a1), y2+head*math.sin(a1)),
                (x2+head*math.cos(a2), y2+head*math.sin(a2))]
        self.d.polygon([(int(x*S),int(y*S)) for x,y in poly], fill="black")

    def text(self, x, y, value, size=21, bold=False, anchor="mm", spacing=4):
        self.d.multiline_text((int(x*S), int(y*S)), value, fill="black", font=self.font(size,bold), anchor=anchor, align="center", spacing=spacing*S)

    def box(self, xy, value, size=23, bold=False, radius=0):
        self.rect(xy, radius)
        x1,y1,x2,y2=xy
        self.text((x1+x2)/2,(y1+y2)/2,value,size,bold)

    def action(self, xy, value, size=21):
        self.box(xy, value, size, radius=14)

    def activity_final(self, x, y):
        self.circle(x, y, 17)
        self.circle(x, y, 10, True)

    def oval(self, xy, value, size=20):
        self.ellipse(xy)
        x1,y1,x2,y2=xy
        self.text((x1+x2)/2,(y1+y2)/2,value,size)

    def diamond(self, cx, cy, rx, ry, value, size=21):
        pts=[(cx,cy-ry),(cx+rx,cy),(cx,cy+ry),(cx-rx,cy)]
        self.line(pts+[pts[0]])
        self.text(cx,cy,value,size)

    def actor(self, x, y, label):
        self.circle(x,y-31,11)
        self.line([(x,y-20),(x,y+20)])
        self.line([(x-22,y-6),(x+22,y-6)])
        self.line([(x,y+20),(x-18,y+47)])
        self.line([(x,y+20),(x+18,y+47)])
        self.text(x,y+72,label,20)

    def save(self, filename):
        # Bilevel output: exactly black and white, including text and strokes.
        mono = self.im.convert("L").point(lambda v: 255 if v >= 170 else 0, mode="1")
        mono.save(ROOT / filename, optimize=True)


def agile():
    f=Figure(1000,300)
    labels=["1  Requirements","2  Design","3  Development","4  Testing","5  Deployment","6  Review"]
    positions=[(20,45,305,120),(355,45,640,120),(690,45,975,120),
               (690,185,975,260),(355,185,640,260),(20,185,305,260)]
    for p,l in zip(positions,labels):f.box(p,l,24,True)
    f.arrow([(305,82),(355,82)]);f.arrow([(640,82),(690,82)])
    f.arrow([(833,120),(833,185)])
    f.arrow([(690,222),(640,222)]);f.arrow([(355,222),(305,222)])
    f.arrow([(163,185),(163,120)])
    f.text(500,150,"Repeat after review",19)
    f.save("figure-01-agile.png")


def context():
    f=Figure(1000,720)
    f.box((365,250,635,500),"0\nStudentShield\nWeb Application",29,True,28)
    actors=[(30,70,255,170,"Student"),(30,310,255,410,"Counselor"),
            (30,550,255,650,"Super Administrator"),(745,70,970,170,"FastAPI ML Service"),
            (745,310,970,410,"Google Meet"),(745,550,970,650,"Parent / Guardian")]
    for x1,y1,x2,y2,label in actors:f.box((x1,y1,x2,y2),label,22,True)
    # Directional context-level flows, with each external entity outside the system boundary.
    f.arrow([(255,105),(450,105),(450,250)])
    f.arrow([(420,250),(420,150),(255,150)])
    f.text(340,78,"submissions",19)
    f.text(332,182,"status / link",19)

    f.arrow([(255,350),(365,350)])
    f.arrow([(365,390),(255,390)])
    f.text(310,322,"review / link",19)
    f.text(310,423,"case data",19)

    f.arrow([(255,575),(420,575),(420,500)])
    f.arrow([(450,500),(450,635),(255,635)])
    f.text(335,545,"user / audit",19)
    f.text(330,680,"records / results",19)

    f.arrow([(550,250),(550,105),(745,105)])
    f.arrow([(745,150),(580,150),(580,250)])
    f.text(680,78,"vent + history",19)
    f.text(675,182,"risk + emotions",19)

    f.arrow([(635,365),(745,365)])
    f.text(690,336,"join redirect",19)

    f.arrow([(550,500),(550,600),(745,600)])
    f.text(680,572,"alert attempt",19)
    f.save("figure-02-context.png")


def student_use_case():
    f=Figure(1000,750)
    f.rect((255,35,950,700),width=3)
    f.text(602,68,"StudentShield system boundary",24,True)
    rows=[(110,"Register / sign in"),(205,"Submit vent"),
          (300,"File bullying report"),(395,"Request counseling"),
          (490,"Open published Meet link"),(595,"Eligible parent alert attempt")]
    for y,label in rows:f.oval((335,y,870,y+65),label,22)
    f.actor(110,315,"Student")
    f.actor(110,620,"Parent / Guardian")
    for y,_ in rows[:5]:f.line([(133,315),(335,y+32)])
    f.line([(133,620),(335,627)])
    f.text(600,728,"Parents are external recipients; no parent portal is implemented.",18)
    f.save("figure-03-student-use-case.png")


def staff_use_case():
    f=Figure(1000,850)
    f.rect((250,35,750,800),width=3)
    f.text(500,68,"StudentShield system boundary",24,True)
    rows=[(110,"Staff sign in + email OTP"),(205,"Review vents / reports"),
          (300,"Verify / reject report"),(395,"Update case notes / status"),
          (490,"Publish Meet link"),(625,"Manage users / roles"),
          (715,"View audit logs")]
    for y,label in rows:f.oval((285,y,715,y+65),label,21)
    f.actor(105,325,"Counselor")
    f.actor(900,355,"Super Admin")
    for y,_ in rows[:5]:
        f.line([(128,325),(285,y+32)])
        f.line([(876,355),(715,y+32)])
    for y,_ in rows[5:]:f.line([(876,355),(715,y+32)])
    f.save("figure-04-staff-use-case.png")


def vent_activity():
    f=Figure(1000,1160)
    f.circle(500,42,14,True)
    f.arrow([(500,56),(500,85)])
    f.action((265,85,735,155),"Student submits vent + optional\nparent-hiding choice",21)
    f.arrow([(500,155),(500,205)])
    f.action((245,205,755,295),"Request ML screening with vent + prior\nrisk history; retain PHP safety net",21)
    f.arrow([(500,295),(500,350)])
    f.diamond(500,405,125,55,"ML response?",22)
    f.arrow([(375,405),(260,405),(260,475)])
    f.arrow([(625,405),(740,405),(740,475)])
    f.text(305,378,"Yes",18);f.text(693,378,"No",18)
    f.action((75,475,445,545),"Use ML result; explicit crisis phrase\nmay override to CRITICAL",19)
    f.action((555,475,925,545),"Use PHP fallback result",21)
    f.line([(260,545),(260,580),(500,580)])
    f.line([(740,545),(740,580),(500,580)])
    f.arrow([(500,580),(500,610)])
    f.action((245,610,755,680),"Persist vent and screening fields",21)
    f.arrow([(500,680),(500,720)])
    f.diamond(500,775,125,55,"Risk level?",22)
    f.arrow([(375,775),(180,775),(180,845)])
    f.arrow([(500,830),(500,845)])
    f.arrow([(625,775),(820,775),(820,845)])
    f.text(247,745,"Low / Medium",18)
    f.text(750,745,"Critical",18)
    f.action((35,845,325,955),"Available for staff\nreview",20)
    f.action((355,845,645,955),"High: counselor alert\nattempt",20)
    f.action((675,845,965,955),"Counselor alert; parent\nattempt only if eligible",20)
    for x in [180,500,820]:f.line([(x,955),(x,1005),(500,1005)])
    f.arrow([(500,1005),(500,1030)])
    f.action((355,1030,645,1080),"Staff follow-up",21)
    f.arrow([(500,1080),(500,1115)])
    f.activity_final(500,1135)
    f.save("figure-05-vent-activity.png")


def incident_activity():
    f=Figure(1000,950)
    f.circle(500,40,14,True);f.arrow([(500,54),(500,90)])
    f.action((245,90,755,165),"Student files self/bystander report\nwith optional evidence",21)
    f.arrow([(500,165),(500,210)])
    f.action((245,210,755,285),"Store protected report/evidence;\nattempt counselor intake alert",21)
    f.arrow([(500,285),(500,335)])
    f.action((290,335,710,400),"Authorized staff review incident",21)
    f.arrow([(500,400),(500,450)])
    f.diamond(500,510,130,60,"Verified?",22)
    f.arrow([(370,510),(250,510),(250,600)])
    f.arrow([(630,510),(750,510),(750,600)])
    f.text(305,478,"Yes",18);f.text(695,478,"No",18)
    f.action((65,600,435,720),"Update report status; attempt\nalerts to resolved victim /\nalleged-bully parent contacts",20)
    f.action((565,600,935,720),"Reject report; no verified-\nincident parent alert",20)
    f.arrow([(750,720),(750,765)])
    f.action((565,765,935,845),"Second false-report strike may\nnotify reporter's parent",19)
    f.line([(250,720),(250,890),(500,890)])
    f.line([(750,845),(750,890),(500,890)])
    f.arrow([(500,890),(500,930)])
    f.activity_final(500,930)
    f.save("figure-06-incident-activity.png")


def meeting_activity():
    f=Figure(1000,1010)
    f.circle(500,40,14,True);f.arrow([(500,54),(500,100)])
    f.action((245,100,755,175),"Student may request counseling\n(or staff may start directly)",21)
    f.arrow([(500,175),(500,230)])
    f.action((245,230,755,305),"Staff create a new Google Meet\noutside StudentShield",21)
    f.arrow([(500,305),(500,360)])
    f.action((245,360,755,435),"Host configures Meet admission\nand confirms settings",21)
    f.arrow([(500,435),(500,490)])
    f.action((245,490,755,565),"Staff publish URL in case summary;\napp rejects reused links",21)
    f.arrow([(500,565),(500,620)])
    f.action((245,620,755,695),"Assigned student and host receive\nJoin access for active session",21)
    f.arrow([(500,695),(500,750)])
    f.action((245,750,755,825),"Join redirects to Google Meet;\nvideo call occurs there",21)
    f.arrow([(500,825),(500,880)])
    f.action((180,880,820,940),"Host closes app access; ends Google call separately",20)
    f.arrow([(500,940),(500,970)])
    f.activity_final(500,990)
    f.save("figure-07-meeting-activity.png")


def entity(f, xy, name, fields):
    x1,y1,x2,y2=xy
    f.rect(xy)
    f.line([(x1,y1+34),(x2,y1+34)])
    f.text((x1+x2)/2,y1+17,name,20,True)
    for n,field in enumerate(fields):
        f.text(x1+11,y1+52+n*23,field,17,anchor="lm")


def erd_a():
    f=Figure(1000,960)
    entity(f,(25,385,315,555),"users",["id  PK","name, role","is_active"])
    boxes=[
        ("students",["user_id  PK, FK","student_id  UNIQUE","parent_phone / email"],(640,20,975,135),"0..1 profile"),
        ("counselors",["user_id  PK, FK","counselor_email / phone"],(640,160,975,270),"0..1 profile"),
        ("admins",["user_id  PK, FK","email / phone"],(640,295,975,405),"0..1 profile"),
        ("vent_logs",["id  PK","user_id  FK","raw_text, risk_level"],(640,435,975,550),"0..* vents"),
        ("bully_reports",["id  PK","reporter_student_id  FK?","victim_student_id  FK?","bully_student_id  FK?"],(640,580,975,730),"0..* reports"),
        ("audit_logs",["id  PK","user_id  FK","action, entity_id"],(640,770,975,885),"0..* audit rows"),
    ]
    starts=[403,430,457,485,510,535]
    for (name,fields,xy,card),sy in zip(boxes,starts):
        entity(f,xy,name,fields)
        ty=(xy[1]+xy[3])/2
        f.line([(315,sy),(400,sy),(540,ty),(640,ty)])
        f.text(590,ty-19,card.split()[0],16)
    f.text(500,925,"FK? = nullable FK; all displayed foreign keys reference users.id.",18)
    f.save("figure-08-erd-a.png")


def erd_b():
    f=Figure(1000,1050)
    entity(f,(25,420,315,590),"users (same table)",["id  PK","student / staff roles"])
    boxes=[
        ("sms_logs",["id  PK","student_id  FK"],(640,20,975,125),"0..* SMS rows"),
        ("email_logs",["id  PK","student_id  FK"],(640,155,975,260),"0..* email rows"),
        ("counselor_notes",["id  PK","student_id  FK","counselor_id  FK"],(640,300,975,430),"two user roles"),
        ("case_status_history",["id  PK","student_id  FK","changed_by  FK"],(640,470,975,600),"two user roles"),
        ("virtual_meetings",["id  PK","student_id  FK","counselor_id  FK?","meet_link, status"],(640,640,975,800),"student + host"),
    ]
    starts=[440,470,500,530,560]
    for (name,fields,xy,card),sy in zip(boxes,starts):
        entity(f,xy,name,fields)
        ty=(xy[1]+xy[3])/2
        f.line([(315,sy),(400,sy),(540,ty),(640,ty)])
        f.text(590,ty-19,"0..*",16)
    f.text(500,870,"All shown foreign keys reference users.id.",19)
    f.text(500,910,"Each user can have 0..* dependent rows; each row names its user role(s).",18)
    f.text(500,950,"virtual_meetings.counselor_id may be null while requested.",18)
    f.save("figure-09-erd-b.png")


if __name__ == "__main__":
    ROOT.mkdir(parents=True, exist_ok=True)
    for func in [agile,context,student_use_case,staff_use_case,vent_activity,incident_activity,meeting_activity]:
        func()
    print("Generated seven bilevel PNG figures in", ROOT)
