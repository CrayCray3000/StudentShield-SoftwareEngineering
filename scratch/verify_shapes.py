# scratch/verify_shapes.py
# Comprehensive DFD shape verification against Yourdon-DeMarco notation
import xml.etree.ElementTree as ET
import os, re

base_dir = r"d:\laragon\www\student shield\docs"
files = {
    "DFD_Level_0.drawio": "Level 0 (Context)",
    "DFD_Level_1.drawio": "Level 1",
    "DFD_Level_2_Vents.drawio": "Level 2 Vents",
    "DFD_Level_2_Reports.drawio": "Level 2 Reports",
}

# Yourdon-DeMarco standard:
# - Process:         Circle/Ellipse  (shape=ellipse)
# - External Entity: Rectangle       (shape=rect or no shape with rounded=0)
# - Data Store:      Open rectangle  (shape=partialRectangle with left=0;right=0)
# - Data Flow:       Arrow           (edge with endArrow)

issues = []

for fname, label in files.items():
    path = os.path.join(base_dir, fname)
    if not os.path.exists(path):
        print(f"[SKIP] {fname} not found")
        continue
    
    print(f"\n{'='*60}")
    print(f"  Verifying: {fname} ({label})")
    print(f"{'='*60}")
    
    tree = ET.parse(path)
    root = tree.getroot()
    
    processes = []
    entities = []
    datastores = []
    edges = []
    other = []
    
    for cell in root.iter("mxCell"):
        cell_id = cell.attrib.get("id", "")
        value = cell.attrib.get("value", "")
        style = cell.attrib.get("style", "")
        is_edge = cell.attrib.get("edge") == "1"
        is_vertex = cell.attrib.get("vertex") == "1"
        
        if cell_id in ("0", "1") or not style:
            continue
        
        if is_edge:
            edges.append((cell_id, value, style))
            continue
        
        if not is_vertex:
            continue
        
        # Classify by ID naming convention
        if cell_id.startswith("p") or cell_id == "sys":
            processes.append((cell_id, value, style))
        elif cell_id.startswith("d") or cell_id.startswith("D"):
            datastores.append((cell_id, value, style))
        elif cell_id.startswith("e") or cell_id in ("student", "counselor", "ml", "parent", "proc4", "proc5"):
            entities.append((cell_id, value, style))
        else:
            other.append((cell_id, value, style))
    
    # --- Check Processes ---
    print(f"\n  PROCESSES ({len(processes)}):")
    for cid, val, sty in processes:
        has_ellipse = "ellipse" in sty
        clean_val = re.sub(r'<[^>]+>', ' ', val).strip()[:40]
        if has_ellipse:
            print(f"    [OK] {cid:10s} -> ellipse (circle)  : {clean_val}")
        else:
            print(f"    [FAIL] {cid:10s} -> NOT ellipse!      : {clean_val}")
            print(f"           Style: {sty[:80]}...")
            issues.append(f"{fname}: Process '{cid}' is NOT an ellipse")
    
    # --- Check External Entities ---
    print(f"\n  EXTERNAL ENTITIES ({len(entities)}):")
    for cid, val, sty in entities:
        is_rect = ("shape=rect" in sty or "rounded=0" in sty) and "ellipse" not in sty and "partialRectangle" not in sty
        # Also accept rounded rectangles for cross-references (proc4, proc5)
        is_crossref = cid.startswith("proc")
        clean_val = re.sub(r'<[^>]+>', ' ', val).strip()[:40]
        if is_rect:
            print(f"    [OK] {cid:10s} -> rectangle          : {clean_val}")
        elif is_crossref:
            print(f"    [INFO] {cid:10s} -> cross-ref (rounded): {clean_val}")
        else:
            print(f"    [FAIL] {cid:10s} -> NOT a rectangle!   : {clean_val}")
            print(f"           Style: {sty[:80]}...")
            issues.append(f"{fname}: Entity '{cid}' is NOT a rectangle")
    
    # --- Check Data Stores ---
    print(f"\n  DATA STORES ({len(datastores)}):")
    for cid, val, sty in datastores:
        has_partial = "partialRectangle" in sty
        has_left_open = "left=0" in sty
        has_right_open = "right=0" in sty
        both_open = has_left_open and has_right_open
        clean_val = re.sub(r'<[^>]+>', ' ', val).strip()[:40]
        
        if has_partial and both_open:
            print(f"    [OK] {cid:10s} -> partialRect L=0,R=0: {clean_val}")
        elif has_partial and has_right_open and not has_left_open:
            print(f"    [WARN] {cid:10s} -> partialRect R=0 ONLY (Gane-Sarson, not Yourdon): {clean_val}")
            issues.append(f"{fname}: Data store '{cid}' uses right=0 only (Gane-Sarson). Level 1 uses left=0;right=0 (Yourdon-DeMarco). INCONSISTENT.")
        elif has_partial:
            print(f"    [WARN] {cid:10s} -> partialRect but sides unclear: {clean_val}")
            issues.append(f"{fname}: Data store '{cid}' partial rectangle but open sides unclear")
        else:
            print(f"    [FAIL] {cid:10s} -> NOT a partialRectangle!: {clean_val}")
            issues.append(f"{fname}: Data store '{cid}' is NOT a partialRectangle")
    
    # --- Check Edges ---
    print(f"\n  DATA FLOWS ({len(edges)}):")
    for cid, val, sty in edges:
        has_arrow = "endArrow" in sty
        if has_arrow:
            print(f"    [OK] {cid:10s} -> has arrow          : {val[:40]}")
        else:
            print(f"    [WARN] {cid:10s} -> NO arrow!          : {val[:40]}")
            issues.append(f"{fname}: Edge '{cid}' has no endArrow")

# --- Summary ---
print(f"\n{'='*60}")
print(f"  SHAPE VERIFICATION SUMMARY")
print(f"{'='*60}")
if not issues:
    print("  ALL SHAPES ARE 100% CORRECT (Yourdon-DeMarco)!")
else:
    print(f"  Found {len(issues)} issue(s):\n")
    for i, issue in enumerate(issues, 1):
        print(f"  {i}. {issue}")
