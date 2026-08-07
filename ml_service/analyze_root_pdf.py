import PyPDF2, re, sys, io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8', errors='replace')

with open('../StudentShield Capstone.pdf', 'rb') as f:
    reader = PyPDF2.PdfReader(f)
    print(f'ROOT PDF: {len(reader.pages)} pages')
    all_text = ''
    for i, page in enumerate(reader.pages):
        if i >= 6:
            all_text += (page.extract_text() or '') + '\n'

def ctx(text, pattern, n=3):
    results = []
    for m in re.finditer(pattern, text, re.IGNORECASE):
        start = max(0, m.start()-100)
        end = min(len(text), m.end()+300)
        s = re.sub(r'\s{2,}', ' ', text[start:end].replace('\n',' ').replace('\r',''))
        s = s.encode('ascii', errors='replace').decode('ascii')
        results.append(s.strip())
    return results[:n]

topics = {
    'SPECIFIC OBJECTIVES': r'specific obj.{0,1000}',
    'TEACHER ROLE': r'teacher.{0,200}(role|account|user|login|access|flowchart)',
    'PARENT PORTAL LOGIN': r'parent.{0,200}(portal|login|account|access|view|dashboard)',
    'GUARDIAN CONSENT': r'guardian consent.{0,300}',
    'VIDEO CONFERENCING': r'video conf.{0,300}|one.on.one.{0,200}session',
    'PDF EXPORT': r'pdf.{0,100}(export|generat|download|report)',
    'REPORT STATUS': r'status of.{0,50}report|report.{0,50}status|track.{0,50}report',
    'COUNSELOR RECOMMEND': r'recommend.{0,200}counselor|counselor.{0,200}recommend',
    'GRADE YEAR LEVEL': r'grade level|year level|grade.{0,50}section',
    'CASE ASSIGNMENT': r'assign.{0,100}(case|report|counsel)',
    'PILOT TEST': r'pilot test.{0,300}',
    'ADMIN SMS': r'admin.{0,100}sms|sms.{0,100}admin',
    'SCOPE SECTION': r'scope.{0,500}',
    'LIMITATIONS': r'limitation.{0,800}',
    'FAKE REPORT FLAGGING': r'fake.{0,200}report|false.{0,200}report|malicious.{0,200}report',
}

for topic, pat in topics.items():
    hits = ctx(all_text, pat)
    if hits:
        print(f'\n=== {topic} ===')
        for h in hits[:2]:
            print(f'  >> {h[:350]}')
