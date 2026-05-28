import requests
url = "http://20.187.146.70:7860/analyze-text"
headers = {"X-API-KEY": "svbsm-production-ml-key-v1", "Content-Type": "application/json"}
data = {
    "raw_text": "Love how people think being the 'funny friend' means you're automatically okay. Meanwhile I'm out here making jokes every five minutes so nobody notices I'm mentally buffering 24/7. But it's fine, really. Nothing screams stability more than saying 'it is what it is' after every emotional breakdown. At this point my coping mechanism is just sarcasm and pretending I'm too tired to care.",
    "student_id": "jc"
}
try:
    resp = requests.post(url, json=data, headers=headers, timeout=10)
    print("STATUS CODE:", resp.status_code)
    import json
    print(json.dumps(resp.json(), indent=2))
except Exception as e:
    print(f"Error: {e}")
