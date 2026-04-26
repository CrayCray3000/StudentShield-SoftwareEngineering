import codecs

xml = """<mxfile host="app.diagrams.net" modified="2024-01-01T00:00:00.000Z" agent="Mozilla/5.0" version="21.0.0" type="device">
  <diagram id="student_shield_erd" name="ERD">
    <mxGraphModel dx="1600" dy="1200" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="1600" pageHeight="1200" math="0" shadow="0">
      <root>
        <mxCell id="0" />
        <mxCell id="1" parent="0" />
"""

TABLES = {
    'users': {
        'x': 600, 'y': 250, 'w': 320, 'color': '#ffffff',
        'cols': [
            ('PK', 'id', 'INT'),
            ('UK', 'student_id', 'VARCHAR'),
            ('', 'name', 'VARCHAR'),
            ('', 'email', 'VARCHAR'),
            ('', 'passcode', 'VARCHAR'),
            ('', 'role', 'TINYINT'),
            ('', 'section', 'VARCHAR'),
            ('', 'adviser_name', 'VARCHAR'),
            ('', 'parent_phone', 'VARCHAR'),
            ('', 'parent_email', 'VARCHAR'),
            ('', 'created_at', 'DATETIME'),
        ]
    },
    'vent_logs': {
        'x': 1100, 'y': 250, 'w': 320, 'color': '#ffffff',
        'cols': [
            ('PK', 'id', 'INT'),
            ('FK', 'user_id', 'INT'),
            ('', 'raw_text', 'TEXT'),
            ('', 'risk_level', 'VARCHAR'),
            ('', 'risk_percent', 'FLOAT'),
            ('', 'status', 'VARCHAR'),
            ('', 'created_at', 'DATETIME'),
            ('', 'deleted_at', 'DATETIME'),
        ]
    },
    'audit_logs': {
        'x': 1100, 'y': 600, 'w': 320, 'color': '#ffffff',
        'cols': [
            ('PK', 'id', 'INT'),
            ('FK', 'user_id', 'INT'),
            ('', 'action', 'VARCHAR'),
            ('', 'entity_type', 'VARCHAR'),
            ('', 'entity_id', 'INT'),
            ('', 'ip_address', 'VARCHAR'),
            ('', 'user_agent', 'VARCHAR'),
            ('', 'created_at', 'DATETIME'),
        ]
    },
    'bully_reports': {
        'x': 100, 'y': 250, 'w': 320, 'color': '#ffffff',
        'cols': [
            ('PK', 'id', 'INT'),
            ('FK', 'reporter_student_id', 'INT'),
            ('FK', 'victim_student_id', 'INT'),
            ('FK', 'bully_student_id', 'INT'),
            ('', 'incident_details', 'TEXT'),
            ('', 'evidence_file', 'VARCHAR'),
            ('', 'status', 'VARCHAR'),
            ('', 'created_at', 'TIMESTAMP'),
            ('', 'deleted_at', 'DATETIME'),
        ]
    },
    'sms_logs': {
        'x': 100, 'y': 650, 'w': 320, 'color': '#ffffff',
        'cols': [
            ('PK', 'id', 'INT'),
            ('FK', 'student_id', 'INT'),
            ('', 'parent_number', 'VARCHAR'),
            ('', 'alert_type', 'VARCHAR'),
            ('', 'message_content', 'TEXT'),
            ('', 'sent_status', 'VARCHAR'),
            ('', 'timestamp', 'DATETIME'),
        ]
    }
}

def generate_table_html(name, data):
    html = f"""<table width="100%" cellpadding="4" cellspacing="0" style="border-collapse:collapse; font-size:12px; font-family:Courier New, monospace;">
    <tr><td colspan="3" align="center" style="font-size:14px; color:#000000; background-color:#ffffff; border-bottom: 2px solid #000000;"><b>{name.upper()}</b></td></tr>"""
    for idx, col in enumerate(data['cols']):
        badge = f"<b style='color:#000000;'>{col[0]}</b>" if col[0] else ""
        base_type = col[2]
        bb = "border-bottom: 1px solid #e0e0e0;" if idx < len(data['cols'])-1 else ""
        bg = "#f9f9f9" if idx % 2 == 0 else "#ffffff"
        html += f"""<tr style="background-color:{bg};">
            <td width="30" align="center" style="border-right: 1px solid #e0e0e0; {bb}">{badge}</td>
            <td width="140" style="color:#000000; {bb}"><b>{col[1]}</b></td>
            <td align="right" style="color:#000000; {bb}">{base_type}</td>
        </tr>"""
    html += "</table>"
    return html.replace('"', '&quot;').replace('<', '&lt;').replace('>', '&gt;')

for name, data in TABLES.items():
    h = 28 + len(data['cols']) * 24
    val = generate_table_html(name, data)
    style = "shape=rectangle;html=1;whiteSpace=wrap;spacing=0;strokeColor=#000000;strokeWidth=2;fillColor=#ffffff;perimeter=rectanglePerimeter;rounded=1;arcSize=2;"
    xml += f"""<mxCell id="{name}" value="{val}" style="{style}" vertex="1" parent="1"><mxGeometry x="{data['x']}" y="{data['y']}" width="{data['w']}" height="{h}" as="geometry" /></mxCell>\n"""

def get_rel_y(table_name, row_idx):
    h = 28 + len(TABLES[table_name]['cols']) * 24
    return (28 + (row_idx * 24) + 12) / h

def edge(id, src, tgt, startArrow, endArrow, exitX=0, exitY=0, entryX=0, entryY=0, waypoints=None, label=""):
    style = f"html=1;strokeColor=#000000;strokeWidth=1.5;startArrow={startArrow};endArrow={endArrow};endFill=0;startFill=0;exitX={exitX};exitY={exitY};entryX={entryX};entryY={entryY};edgeStyle=orthogonalEdgeStyle;rounded=1;jumpStyle=arc;jumpSize=6;"
    points_xml = ""
    if waypoints:
        points_xml = '<Array as="points">'
        for pt in waypoints:
            points_xml += f'<mxPoint x="{pt[0]}" y="{pt[1]}" />'
        points_xml += '</Array>'
    
    edge_xml = f"""<mxCell id="{id}" value="" style="{style}" edge="1" parent="1" source="{src}" target="{tgt}"><mxGeometry relative="1" as="geometry">{points_xml}</mxGeometry></mxCell>\n"""
    return edge_xml

def abs_y(table, row):
    return TABLES[table]['y'] + 28 + (row * 24) + 12

def abs_x(table, is_right):
    return TABLES[table]['x'] + (TABLES[table]['w'] if is_right else 0)

# Relationship 1: Reporter -> User
xml += edge("E1", "users", "bully_reports", "ERzeroToOne", "ERzeroToMany",
            exitX=0, exitY=get_rel_y("users", 0), 
            entryX=1, entryY=get_rel_y("bully_reports", 1),
            waypoints=[(abs_x("users", False) - 40, abs_y("users", 0)), (abs_x("users", False) - 40, abs_y("bully_reports", 1))])

# Relationship 2: Victim -> User
xml += edge("E2", "users", "bully_reports", "ERzeroToOne", "ERzeroToMany",
            exitX=0, exitY=get_rel_y("users", 0), 
            entryX=1, entryY=get_rel_y("bully_reports", 2),
            waypoints=[(abs_x("users", False) - 60, abs_y("users", 0)), (abs_x("users", False) - 60, abs_y("bully_reports", 2))])

# Relationship 3: Bully -> User
xml += edge("E3", "users", "bully_reports", "ERzeroToOne", "ERzeroToMany",
            exitX=0, exitY=get_rel_y("users", 0), 
            entryX=1, entryY=get_rel_y("bully_reports", 3),
            waypoints=[(abs_x("users", False) - 80, abs_y("users", 0)), (abs_x("users", False) - 80, abs_y("bully_reports", 3))])

# Relationship 4: Venter -> User
xml += edge("E4", "users", "vent_logs", "ERmandOne", "ERzeroToMany",
            exitX=1, exitY=get_rel_y("users", 0), 
            entryX=0, entryY=get_rel_y("vent_logs", 1),
            waypoints=[(abs_x("users", True) + 40, abs_y("users", 0)), (abs_x("users", True) + 40, abs_y("vent_logs", 1))])

# Relationship 5: Actor -> User
xml += edge("E5", "users", "audit_logs", "ERzeroToOne", "ERzeroToMany",
            exitX=1, exitY=get_rel_y("users", 0), 
            entryX=0, entryY=get_rel_y("audit_logs", 1),
            waypoints=[(abs_x("users", True) + 80, abs_y("users", 0)), (abs_x("users", True) + 80, abs_y("audit_logs", 1))])

# Relationship 6: Student -> User (SMS Logs)
xml += edge("E6", "users", "sms_logs", "ERzeroToOne", "ERzeroToMany",
            exitX=0, exitY=get_rel_y("users", 0), 
            entryX=1, entryY=get_rel_y("sms_logs", 1),
            waypoints=[(abs_x("users", False) - 100, abs_y("users", 0)), (abs_x("users", False) - 100, abs_y("sms_logs", 1))])

xml += """      </root>
    </mxGraphModel>
  </diagram>
</mxfile>"""

with open("docs/erd_diagram.drawio", "w", encoding="utf-8") as f: f.write(xml)
print("SUCCESS")
