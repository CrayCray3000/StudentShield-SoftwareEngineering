import json

with open(r'C:\Users\drvon\.gemini\antigravity\brain\77bee6b9-854b-4282-9747-8ed6cfea9b9e\.system_generated\logs\transcript.jsonl', 'r', encoding='utf-8') as f:
    lines = f.readlines()

print(f"Total lines: {len(lines)}")
user_inputs = []
for i, line in enumerate(lines):
    try:
        obj = json.loads(line)
        if obj.get('source') == 'USER_EXPLICIT':
            user_inputs.append((i, obj))
    except Exception:
        pass

print(f"Total user explicit messages: {len(user_inputs)}")
print("Last 15 user messages:")
for idx, obj in user_inputs[-15:]:
    print(f"Index: {idx} | Step {obj.get('step_index')} | Time: {obj.get('created_at')}")
    print("  USER:", repr(obj.get('content'))[:500])
    # Let's find the model response after this user input
    for j in range(idx + 1, min(idx + 10, len(lines))):
        try:
            m_obj = json.loads(lines[j])
            if m_obj.get('source') == 'MODEL' and m_obj.get('type') == 'PLANNER_RESPONSE':
                if m_obj.get('content'):
                    print("  MODEL RESPONSE:", repr(m_obj.get('content'))[:400])
                if m_obj.get('tool_calls'):
                    print("  MODEL TOOLS:", m_obj.get('tool_calls'))
                break
        except Exception:
            pass
    print("-" * 50)
