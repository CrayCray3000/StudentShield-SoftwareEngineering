with open('docs/Capstone Project 1.txt', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, line in enumerate(lines, 1):
    if 'host' in line.lower() or 'fee' in line.lower() or 'budget' in line.lower() or 'domain' in line.lower():
        print(f"Line {i}: {line.strip()[:160]}")
