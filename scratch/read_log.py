import json
import os

log_path = r"C:\Users\drvon\.gemini\antigravity\brain\f4fe9269-97ac-4769-ade3-57b825b15810\.system_generated\logs\overview.txt"

with open(log_path, "r", encoding="utf-8") as f:
    for line in f:
        line = line.strip()
        if not line:
            continue
        try:
            obj = json.loads(line)
            step = obj.get('step_index', 0)
            # Let's inspect steps between 200 and 260
            if 200 <= step <= 340:
                print(f"=== STEP {step} ({obj.get('source')}) ===")
                if obj.get('content'):
                    print(obj.get('content')[:1000]) # print first 1000 chars of content
                elif obj.get('tool_calls'):
                    print("Tool Calls:", obj.get('tool_calls'))
                elif obj.get('response'):
                    print("Response:", str(obj.get('response'))[:1000])
                print("-" * 50)
        except Exception as e:
            pass
