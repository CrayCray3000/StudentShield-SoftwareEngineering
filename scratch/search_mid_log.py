import json

log_path = r"C:\Users\drvon\.gemini\antigravity\brain\f4fe9269-97ac-4769-ade3-57b825b15810\.system_generated\logs\overview.txt"

with open(log_path, "r", encoding="utf-8") as f:
    for line in f:
        line = line.strip()
        if not line:
            continue
        try:
            obj = json.loads(line)
            step = obj.get('step_index', 0)
            if 200 <= step < 255:
                if obj.get('source') == 'USER_EXPLICIT':
                    print(f"=== STEP {step} (USER) ===")
                    print(obj.get('content'))
                    print("-" * 50)
                elif obj.get('source') == 'MODEL' and any(kw in str(obj).lower() for kw in ['suicide', 'metrics', 'website', 'gad', 'phq']):
                    print(f"=== STEP {step} (MODEL) ===")
                    if obj.get('content'):
                        print(obj.get('content')[:1000])
                    elif obj.get('response'):
                        print(str(obj.get('response'))[:1000])
                    print("-" * 50)
        except Exception as e:
            pass
