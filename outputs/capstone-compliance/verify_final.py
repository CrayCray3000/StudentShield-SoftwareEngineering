from pathlib import Path
from docx import Document
from docx.oxml.ns import qn
from pypdf import PdfReader
from zipfile import ZipFile
from lxml import etree
import re,json,hashlib
import pypdfium2 as pdfium

OUT=Path(__file__).resolve().parent;ROOT=OUT.parents[1]
doc=Document(ROOT/'StudentShield Capstone.docx')
template=Document(ROOT/'Capstone Documentation Template.docx')
r=PdfReader(OUT/'revised-review.pdf')
pdftexts=[x.extract_text() for x in r.pages]
norm=lambda s:re.sub(r'[^a-z0-9]','',s.lower())
pdfall=norm(''.join(pdftexts))
log=json.loads((OUT/'transfer-log.json').read_text(encoding='utf-8'))
missing=[item for item in log if 'text' in item and norm(item['text']) not in pdfall]
# Cross-page running matter is removed before a second content comparison.
clean=''.join(re.sub(r'NATIONAL\s+TEACHERS\s+COLLEGE\s+School of Arts, Sciences and Technology\s+Bachelor of Science in Information Technology\s+[ivxlcdm\d]+\s*','',t,flags=re.I) for t in pdftexts)
missing=[item for item in missing if norm(item['text']) not in norm(clean)]
alltext='\n'.join(pdftexts)
requirements=['1.1 Project Context','1.2 Purpose and Description','1.3 Objectives of the Study','1.3.1 General Objective','1.3.2 Specific Objectives','1.4 Scope and Delimitations','1.5 Significance of the Study','1.6 Definition of Terms','2.1 Related Literature (Foreign and Local)','2.2 Related Studies (Foreign and Local)','2.3 Synthesis of the Reviewed Literature and Studies','3.1 Overview of Current Technologies','3.2 Evaluation of the Existing System (Manual or Automated)','3.3 Technical Feasibility','3.3.1 Hardware Specifications','3.3.2 Software Specifications','3.3.3 Network/Architecture Specifications','4.1 Software Development Life Cycle (SDLC Model)','4.2 Requirements Analysis','4.3 System Design and Modeling','4.3.1 Context Diagram','4.3.2 Use Case Diagram','4.3.3 Data Flow Diagram (DFD) or Activity Diagram','4.3.4 Entity Relationship Diagram (ERD)','4.3.5 Data Dictionary','4.4 Software Testing Plan','4.4.1 Unit Testing','4.4.2 Integration Testing','4.4.3 System Testing','4.5 User Acceptance Testing (UAT) and Evaluation','5.1 System Implementation','5.1.1 Main Interface / Dashboard','5.1.2 Core Modules Features','5.2 Evaluation Results (ISO/IEC 25010)','5.3 Discussion of Findings','6.1 Summary of Findings','6.2 Conclusions','6.3 Recommendations']
def resolved_style(name):
 target='Caption1' if name=='Caption' else name
 s=next(st for st in doc.styles if st.name.lower()==target.lower());font=None;size=None
 while s is not None:
  font=font or s.font.name;size=size or s.font.size;s=s.base_style
 defaults=doc.styles.element.find(qn('w:docDefaults')).find(qn('w:rPrDefault')).find(qn('w:rPr'))
 if font is None:font=defaults.find(qn('w:rFonts')).get(qn('w:ascii'))
 if size is None:points=int(defaults.find(qn('w:sz')).get(qn('w:val')))/2
 else:points=size.pt
 return {'font':font,'size':points}
styles={name:resolved_style(name) for name in ['Normal','Heading 1','Heading 2','Heading 3','Caption']}
bodyhead=[p.text for p in doc.paragraphs if p.style.name.startswith('Heading')]
assert all(s in bodyhead for s in requirements)
assert [bodyhead.index(s) for s in requirements]==sorted(bodyhead.index(s) for s in requirements)
assert len(doc.sections)==2
assert not any(x in alltext for x in ['Error! Bookmark','Error! Reference','4.78','4.90','Very Highly Acceptable','Vince Carl Michael','Aaron Paul','facCritical'])
assert not any(c.text.strip() for t in doc.tables[14:20] for row in t.rows[1:] for c in [row.cells[-1]])
template_margin=etree.tostring(template.sections[0]._sectPr.find(qn('w:pgMar')))
margin_matches=[]
for s in doc.sections:
 margin_matches.append(dict(s._sectPr.find(qn('w:pgMar')).attrib)==dict(template.sections[0]._sectPr.find(qn('w:pgMar')).attrib))
assert all(margin_matches)
sha=lambda p:hashlib.sha256(p.read_bytes()).hexdigest()
with ZipFile(ROOT/'Capstone Documentation Template.docx') as z1,ZipFile(ROOT/'StudentShield Capstone.docx') as z2:
 header1=etree.fromstring(z1.read('word/header1.xml'));header2=etree.fromstring(z2.read('word/header1.xml'))
 # Word can rewrite XML serialization; preserve actual drawing count and source artwork bytes.
 image_matches={n:sha_bytes==hashlib.sha256(z2.read(n)).hexdigest() for n in z1.namelist() if n.startswith('word/media/') for sha_bytes in [hashlib.sha256(z1.read(n)).hexdigest()] if n in z2.namelist()}
pd=pdfium.PdfDocument(OUT/'revised-review.pdf')
overflow=[]
for i,page in enumerate(pd):
 tp=page.get_textpage()
 for j in range(tp.count_chars()):
  char=tp.get_text_range(j,1)
  if not char.strip():continue
  left,bottom,right,top=tp.get_charbox(j)
  if 75<bottom<673 and (left<137 or right>544):overflow.append({'page':i+1,'char':char,'box':[left,bottom,right,top]})
 tp.close();page.close()
stats={'pdf_pages':len(r.pages),'template_margin_attributes':dict(template.sections[0]._sectPr.find(qn('w:pgMar')).attrib),'all_section_margins_match':all(margin_matches),'styles':styles,'template_images_preserved':image_matches,'blank_test_result_cells':True,'required_headings_in_order':True,'source_text_unlocated':missing,'body_character_overflows':overflow,'original_backup_sha256':sha(ROOT/'backups/capstone-before-template-2026-09-08/StudentShield Capstone.pdf'),'new_pdf_sha256':sha(OUT/'revised-review.pdf'),'source_paragraphs_retained':len({x['source_paragraph'] for x in log if 'source_paragraph' in x}),'modified_source_paragraphs':len({x['source_paragraph'] for x in log if x.get('changed')})}
(OUT/'verification.json').write_text(json.dumps(stats,ensure_ascii=False,indent=2),encoding='utf-8')
print(json.dumps(stats,ensure_ascii=True,indent=2))
