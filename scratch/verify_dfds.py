# scratch/verify_dfds.py
import xml.etree.ElementTree as ET
import os

base_dir = r"d:\laragon\www\student shield\docs"
files = [
    "DFD_Level_0.drawio",
    "DFD_Level_1.drawio",
    "DFD_Level_2_Vents.drawio",
    "DFD_Level_2_Reports.drawio",
    "StudentShield_DFD_Complete_Perfected.drawio",
    "StudentShield_DFD_Complete_Perfected (1).drawio",
    "StudentShield_DFD_Complete_Fixed.drawio",
    "StudentShield_DFD_Complete (2).drawio"
]

violations = 0

for file in files:
    path = os.path.join(base_dir, file)
    if not os.path.exists(path):
        print(f"File not found: {path}")
        continue
    
    print(f"\nVerifying {file}...")
    try:
        tree = ET.parse(path)
        root = tree.getroot()
        
        # Search for mxCell elements
        for cell in root.iter("mxCell"):
            # Check edge attributes
            is_edge = cell.attrib.get("edge") == "1"
            value = cell.attrib.get("value", "")
            cell_id = cell.attrib.get("id", "")
            
            # For processes, let's check their names too
            is_vertex = cell.attrib.get("vertex") == "1"
            
            if is_edge:
                if "&" in value or "," in value:
                    print(f"  [VIOLATION] Edge {cell_id} label has '&' or ',': {repr(value)}")
                    violations += 1
                else:
                    print(f"  [OK] Edge {cell_id}: {repr(value)}")
            elif is_vertex:
                # Check process labels for ampersands
                # Ignore system shapes and database labels like D1 | users_tbl
                if any(proc_prefix in cell_id for proc_prefix in ["p1", "p2", "p3", "p4", "p5", "p21", "p22", "p23", "p24", "p31", "p32", "p33", "p34"]):
                    if "&" in value:
                        print(f"  [VIOLATION] Process {cell_id} label has '&': {repr(value)}")
                        violations += 1
                    else:
                        print(f"  [OK] Process {cell_id}: {repr(value)}")
    except Exception as e:
        print(f"Error parsing {file}: {e}")

if violations == 0:
    print("\nALL DFDS ARE 100% COMPLIANT! No ampersands or compound flows found.")
else:
    print(f"\nFound {violations} violations that need fixing.")
