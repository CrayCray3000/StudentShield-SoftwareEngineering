import re

files_to_check = [
    r"d:\laragon\www\student shield\ml_service\main.py",
    r"d:\laragon\www\student shield\web-app-ci4\app\Controllers\VentController.php"
]

patterns = [r"\bgad\b", r"\bphq\b", r"\bcart\b", r"anxiety", r"depression", r"severity", r"classification"]

output_lines = []

for fp in files_to_check:
    output_lines.append(f"\n=================== {fp} ===================")
    with open(fp, "r", encoding="utf-8", errors="ignore") as f:
        lines = f.readlines()
    for idx, line in enumerate(lines):
        for pattern in patterns:
            if re.search(pattern, line, re.IGNORECASE):
                output_lines.append(f"Line {idx+1}: {line.strip()}")
                break

with open(r"d:\laragon\www\student shield\scratch\extracted_lines.txt", "w", encoding="utf-8") as f:
    f.write("\n".join(output_lines))
