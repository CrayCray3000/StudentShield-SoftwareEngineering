from duckduckgo_search import DDGS
import json

queries = [
    'cyberbullying detection natural language processing machine learning ieee',
    'sentiment analysis student mental health education system semanticscholar',
    'Filipino Tagalog sentiment analysis cyberbullying researchgate',
    'cyberbullying impact students Philippines school journal'
]

results = []
try:
    with DDGS() as ddgs:
        for q in queries:
            ans = list(ddgs.text(q, max_results=15))
            results.extend(ans)
except Exception as e:
    print(f"Error: {e}")

with open('ddg.json', 'w', encoding='utf-8') as f:
    json.dump(results, f, indent=2)
print("Done!")
