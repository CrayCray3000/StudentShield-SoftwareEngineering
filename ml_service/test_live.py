import requests
import json

url = "http://20.187.146.70:7860/analyze"
headers = {
    "X-API-KEY": "student_shield_secure_api_key_2024",
    "Content-Type": "application/json"
}

text1 = "I'm so tired of acting like everything's okay when honestly it feels like I'm falling apart every single day. People keep saying things will get better, but it's hard to believe that when I can barely get through the day without feeling hopeless. I've been shutting everyone out, not sleeping properly, and constantly thinking about how badly I want everything to stop for a while. I laugh and joke around so nobody notices, but deep down I feel completely lost and exhausted."
text2 = "Yeah, no, I'm doing GREAT actually. Totally normal to smile all day then suddenly feel empty the second I'm alone. Super fun. My favorite part is overthinking every tiny thing I said like it's a full-time job. And honestly? Shoutout to my brain for turning small problems into cinematic emotional disasters at 3AM. Very creative. Very talented. Truly thriving."

for idx, text in enumerate([text1, text2]):
    payload = {"text": text, "student_id": "jc"}
    response = requests.post(url, json=payload, headers=headers)
    print(f"--- TEXT {idx+1} ---")
    print(json.dumps(response.json(), indent=2))
    print()
