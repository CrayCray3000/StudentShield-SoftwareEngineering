import sys
import json
import asyncio
from main import analyze_text, TextPayload

async def test():
    text = """Oh wow, absolutely fantastic. I just love waking up every day to the same stress, the same disappointment, and the same people telling me “things will get better” like they’re reading from a motivational calendar they found in a dentist office. Honestly, what a privilege it is to constantly carry everyone else’s problems while barely holding myself together. Truly living the dream here.

And my favorite part? The way people suddenly care the moment I stop pretending I’m okay. That’s always such a heartwarming surprise. Nothing says support quite like ignoring someone until they’re visibly exhausted. But hey, at least I’ve mastered the skill of smiling while mentally collapsing, so that’s a fun little talent I can put on my résumé someday.

Seriously though, I’m so lucky everything falls apart at the exact worst possible moment every single time. The consistency is comforting. I’d hate for life to become predictable in a good way and ruin the character development arc everyone seems so obsessed with."""
    payload = TextPayload(raw_text=text)
    
    # We need to bypass the Depends(verify_api_key) for a direct function call
    # Actually, analyze_text expects a Request and a payload.
    # It might be easier to use httpx to hit the running server or just extract the logic.
    pass

if __name__ == "__main__":
    import requests
    url = "http://127.0.0.1:8000/analyze-text"
    headers = {"X-API-KEY": "svbsm-production-ml-key-v1", "Content-Type": "application/json"}
    data = {
        "raw_text": "Oh wow, absolutely fantastic. I just love waking up every day to the same stress, the same disappointment, and the same people telling me 'things will get better' like they're reading from a motivational calendar they found in a dentist office. Honestly, what a privilege it is to constantly carry everyone else's problems while barely holding myself together. Truly living the dream here. And my favorite part? The way people suddenly care the moment I stop pretending I'm okay. That's always such a heartwarming surprise. Nothing says support quite like ignoring someone until they're visibly exhausted. But hey, at least I've mastered the skill of smiling while mentally collapsing, so that's a fun little talent I can put on my resume someday. Seriously though, I'm so lucky everything falls apart at the exact worst possible moment every single time. The consistency is comforting. I'd hate for life to become predictable in a good way and ruin the character development arc everyone seems so obsessed with.",
        "student_id": "test_123"
    }
    try:
        resp = requests.post(url, json=data, headers=headers)
        print(resp.json())
    except Exception as e:
        print(f"Error: {e}")
