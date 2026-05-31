import os

search_dir = r"d:\laragon\www\student shield"
terms = ["gad", "phq", "cart", "anxiety", "depression", "severity", "classification", "rule", "decision tree"]

for root, dirs, files in os.walk(search_dir):
    # Prune unwanted directories in-place
    dirs[:] = [d for d in dirs if d not in (".venv", ".git", "node_modules", "vendor", "writable", "third_party", "public")]
    
    for file in files:
        if file.endswith((".py", ".php", ".html", ".js", ".json")):
            filepath = os.path.join(root, file)
            try:
                with open(filepath, "r", encoding="utf-8", errors="ignore") as f:
                    content = f.read()
                found = []
                for term in terms:
                    if term in content.lower():
                        found.append(term)
                if found:
                    print(f"File: {filepath} contains terms: {found}")
            except Exception as e:
                pass
