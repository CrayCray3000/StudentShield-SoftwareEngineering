xml = """<mxfile host="app.diagrams.net" modified="2024-01-01T00:00:00.000Z" agent="Mozilla/5.0" version="21.0.0" type="device">
  <diagram id="student_shield_arch" name="Architecture">
    <mxGraphModel dx="1600" dy="1200" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="1600" pageHeight="1200" math="0" shadow="0">
      <root>
        <mxCell id="0" />
        <mxCell id="1" parent="0" />
"""

THEMES = {
    'blue': ('#dae8fc', '#6c8ebf'),
    'purple': ('#e1d5e7', '#9673a6'),
    'orange': ('#ffe6cc', '#d79b00'),
    'green': ('#d5e8d4', '#82b366'),
    'red': ('#f8cecc', '#b85450'),
    'yellow': ('#fff2cc', '#d6b656'),
    'gray': ('#f5f5f5', '#666666')
}

def group(id, name, x, y, w, h, theme_key):
    fill, stroke = THEMES[theme_key]
    return f"""<mxCell id="{id}" value="{name}" style="rounded=1;whiteSpace=wrap;html=1;fillColor=none;strokeColor={stroke};strokeWidth=2;verticalAlign=top;spacingTop=10;fontStyle=1;fontColor={stroke};align=left;spacingLeft=10;dashed=1;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry" /></mxCell>\n"""

def node(id, name, icon, x, y, theme_key, w=120, h=70, shape="rounded=1"):
    fill, stroke = THEMES[theme_key]
    value = f"&lt;div style=&quot;text-align:center; font-size:24px; margin-bottom:4px;&quot;&gt;{icon}&lt;/div&gt;&lt;div style=&quot;font-size:12px; font-weight:600; color:#111;&quot;&gt;{name}&lt;/div&gt;"
    return f"""<mxCell id="{id}" value="{value}" style="shape={shape};whiteSpace=wrap;html=1;fillColor={fill};strokeColor={stroke};strokeWidth=2;fontColor=#111111;fontStyle=1;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry" /></mxCell>\n"""

def edge(id, src, tgt, theme_key, pts=None, exitX=None, exitY=None, entryX=None, entryY=None):
    fill, stroke = THEMES[theme_key]
    style = f"edgeStyle=orthogonalEdgeStyle;rounded=1;html=1;strokeColor={stroke};strokeWidth=2;"
    if exitX is not None: style += f"exitX={exitX};exitY={exitY};"
    if entryX is not None: style += f"entryX={entryX};entryY={entryY};"
    pts_xml = '<mxPoint as="sourcePoint" x="0" y="0"/><mxPoint as="targetPoint" x="0" y="0"/>'
    if pts: pts_xml += '<Array as="points">' + "".join([f'<mxPoint x="{p[0]}" y="{p[1]}" />' for p in pts]) + '</Array>'
    return f"""<mxCell id="{id}" style="{style}" edge="1" parent="1" source="{src}" target="{tgt}"><mxGeometry relative="1" as="geometry">{pts_xml}</mxGeometry></mxCell>\n"""

xml += group('G_Users', '👥 USERS', 50, 50, 200, 360, 'blue')
xml += group('G_Views', '🖥️ PRESENTATION / VIEWS', 50, 430, 200, 500, 'purple')
xml += group('G_Filters', '🚦 CI4 ROUTING &amp; FILTERS', 350, 50, 250, 360, 'orange')
xml += group('G_Ctrls', '⚙️ CONTROLLERS &amp; LOGIC', 350, 430, 250, 630, 'green')
xml += group('G_AI', '🧠 AI RISK ENGINE', 700, 50, 250, 360, 'red')
xml += group('G_Alerts', '📡 EXTERNAL ALERTS', 700, 430, 250, 400, 'yellow')
xml += group('G_Data', '🗄️ DATA STORAGE', 1050, 430, 200, 300, 'gray')

xml += node('N_Stu', 'Student', '👨‍🎓', 90, 100, 'blue')
xml += node('N_Cou', 'Counselor', '👨‍⚕️', 90, 220, 'blue')
xml += node('N_Adm', 'Super Admin', '🛡️', 90, 340, 'blue')

xml += node('N_H5', 'HTML5 / CSS3', '🌐', 90, 500, 'purple')
xml += node('N_TW', 'Tailwind CSS', '🎨', 90, 600, 'purple')
xml += node('N_JS', 'JS &amp; AJAX', '⚡', 90, 700, 'purple')
xml += node('N_Chart', 'Chart.js', '📊', 90, 800, 'purple')

xml += node('N_Route', 'CI4 Router', '🛣️', 415, 100, 'orange')
xml += node('N_IP', 'IP &amp; Rate Limit', '🚥', 415, 220, 'orange')
xml += node('N_AuthF', 'AuthFilter (RBAC)', '🛂', 415, 340, 'orange')

xml += node('N_AuthC', 'Auth Controller', '🔑', 415, 480, 'green')
xml += node('N_DashC', 'Dashboard Ctrl', '🖥️', 415, 580, 'green')
xml += node('N_VentC', 'Vent Controller', '📝', 415, 680, 'green')
xml += node('N_BullyC', 'Bully Controller', '⚠️', 415, 780, 'green')
xml += node('N_Audit', 'Audit Logger', '📋', 415, 880, 'green')
xml += node('N_AES', 'AES-256 Crypto', '🔒', 415, 980, 'green')

xml += node('N_HF', 'Hugging Face API', '🤖', 765, 100, 'red')
xml += node('N_Loc', 'Local Keyword Eng', '🔍', 765, 220, 'red')
xml += node('N_Score', 'Risk Scorer', '⚖️', 765, 340, 'red')

xml += node('N_Phil', 'PhilSMS Gateway', '📱', 765, 500, 'yellow')
xml += node('N_Gmail', 'Gmail SMTP', '📧', 765, 620, 'yellow')
xml += node('N_Par', 'Parent', '👨‍👩‍👦', 765, 740, 'yellow')

xml += node('N_DB', 'MySQL Database', '🛢️', 1090, 500, 'gray', 120, 90, 'cylinder3')
xml += node('N_JSON', 'JSON Audit Logs', '📄', 1090, 660, 'gray')

# Lines
xml += edge('E1', 'N_Stu', 'N_Route', 'blue', [], 1, 0.5, 0, 0.5)
xml += edge('E2', 'N_Cou', 'N_Route', 'blue', [(380, 250), (380, 140)], 1, 0.5, 0, 0.66)
xml += edge('E3', 'N_Adm', 'N_Route', 'blue', [(370, 370), (370, 150)], 1, 0.5, 0, 0.83)

xml += edge('E4', 'N_Route', 'N_IP', 'orange', [], 0.5, 1, 0.5, 0)
xml += edge('E5', 'N_IP', 'N_AuthF', 'orange', [], 0.5, 1, 0.5, 0)

xml += edge('E6', 'N_AuthF', 'N_AuthC', 'orange', [], 0.5, 1, 0.5, 0)
xml += edge('E7', 'N_AuthF', 'N_DashC', 'orange', [(380, 400), (380, 610)], 0.5, 1, 0, 0.5)
xml += edge('E8', 'N_AuthF', 'N_VentC', 'orange', [(370, 400), (370, 710)], 0.5, 1, 0, 0.5)
xml += edge('E9', 'N_AuthF', 'N_BullyC', 'orange', [(360, 400), (360, 810)], 0.5, 1, 0, 0.5)

xml += edge('E10', 'N_VentC', 'N_AES', 'green', [(475, 740), (475, 980)], 0.5, 1, 0.5, 0)

xml += edge('E11', 'N_AuthC', 'N_H5', 'green', [], 0, 0.5, 1, 0.16)
xml += edge('E12', 'N_DashC', 'N_H5', 'green', [(340, 610), (340, 530)], 0, 0.5, 1, 0.5)
xml += edge('E13', 'N_DashC', 'N_TW', 'green', [(340, 610), (340, 630)], 0, 0.5, 1, 0.5)
xml += edge('E14', 'N_DashC', 'N_JS', 'green', [(340, 610), (340, 730)], 0, 0.5, 1, 0.5)
xml += edge('E15', 'N_DashC', 'N_Chart', 'green', [(340, 610), (340, 830)], 0, 0.5, 1, 0.5)

xml += edge('E16', 'N_VentC', 'N_HF', 'green', [(660, 710), (660, 130)], 1, 0.5, 0, 0.5)
xml += edge('E17', 'N_VentC', 'N_Loc', 'green', [(650, 710), (650, 250)], 1, 0.5, 0, 0.5)

xml += edge('E18', 'N_HF', 'N_Score', 'red', [], 0.5, 1, 0.5, 0)
xml += edge('E19', 'N_Loc', 'N_Score', 'red', [], 0.5, 1, 0.5, 0)

xml += edge('E20', 'N_Score', 'N_Phil', 'red', [(900, 370), (900, 480), (710, 480), (710, 530)], 1, 0.5, 0, 0.5)
xml += edge('E21', 'N_Score', 'N_Gmail', 'red', [(910, 370), (910, 470), (700, 470), (700, 650)], 1, 0.5, 0, 0.5)
xml += edge('E22', 'N_Score', 'N_DB', 'red', [(1020, 370), (1020, 530)], 1, 0.5, 0, 0.3)

xml += edge('E23', 'N_BullyC', 'N_Phil', 'green', [(690, 810), (690, 550)], 1, 0.5, 0, 0.8)
xml += edge('E24', 'N_BullyC', 'N_Gmail', 'green', [(680, 810), (680, 670)], 1, 0.5, 0, 0.8)
xml += edge('E25', 'N_BullyC', 'N_DB', 'green', [(1050, 810), (1050, 550)], 1, 0.5, 0, 0.6)

xml += edge('E26', 'N_AuthC', 'N_DB', 'green', [], 1, 0.5, 0, 0.1)
xml += edge('E27', 'N_DashC', 'N_DB', 'green', [(1040, 610), (1040, 570)], 1, 0.5, 0, 0.8)
xml += edge('E28', 'N_Audit', 'N_JSON', 'green', [(1040, 910), (1040, 690)], 1, 0.5, 0, 0.5)

xml += edge('E29', 'N_Phil', 'N_Par', 'yellow', [(900, 530), (900, 770), (815, 770)], 1, 0.5, 0.5, 1)
xml += edge('E30', 'N_Gmail', 'N_Par', 'yellow', [(890, 650), (890, 770), (815, 770)], 1, 0.5, 0.5, 1)

xml += """      </root>
    </mxGraphModel>
  </diagram>
</mxfile>"""
with open('d:/laragon/www/student shield/docs/architecture_diagram.drawio', 'w', encoding='utf-8') as f: f.write(xml)
