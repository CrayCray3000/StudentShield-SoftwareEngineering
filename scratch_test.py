import sys
import os
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from ml_service.main import analyze_text_sync
text = "Honestly, life's kinda funny. You spend the whole day pretending you're chill, then the moment you finally lie down your brain goes, 'hey remember every embarrassing thing, every problem, and every reason you feel unwanted?' Like wow, thanks. Such a relaxing nighttime activity. And of course I tell everyone I'm fine because apparently becoming emotionally unavailable with a sense of humor counts as character development now."
res = analyze_text_sync(text, student_id="jc")
print("RISK:", res.get("risk_level"))
print("SCORE:", res.get("risk_score"))
print("REASON:", res.get("reasoning"))
