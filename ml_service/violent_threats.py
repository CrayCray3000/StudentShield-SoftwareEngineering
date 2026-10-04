"""Deterministic safety floor for explicit, first-person threats to others."""

import re


# Require an intent/action and a human target; anger or a knife alone is not enough.
THREATS = [
    re.compile(pattern, re.IGNORECASE)
    for pattern in [
        r"\b(?:papatayin|patayin|sasaksakin|saksakin|babarilin|barilin)\s+"
        r"(?:(?:na|lang)\s+)*(?:(?:ko|namin|natin)\s+(?:(?:na|lang|lahat)\s+)*"
        r"(?:(?:sila|siya|kayo|ka)(?:ng)?|lahat|mga\s+(?:kaibigan|kaklase)|si\s+\w+)|kita)\b",
        r"\b(?:i\s+(?:will|want\s+to|plan\s+to|intend\s+to|am\s+going\s+to)|"
        r"i['’](?:ll|m\s+(?:going\s+to|gonna))|i\s+gonna)\s+"
        r"(?:kill|murder|stab|shoot)\s+(?:you|him|her|them|everyone|everybody|"
        r"all\s+of\s+(?:you|them)|my\s+(?:classmates?|friends?|teacher))\b",
    ]
]
NEGATED_PREFIX = re.compile(
    r"\b(?:(?:hindi|di)(?:\s+naman)?\s+(?:totoo(?:ng)?(?:\s+na)?|"
    r"(?:ko\s+)?(?:sinabi|sinasabi|sasabihin)\s+na)|"
    r"(?:ayoko|huwag|wag)\s+(?:nang\s+)?sabihin\s+na|not\s+true\s+that|never\s+said)\s*$",
    re.IGNORECASE,
)
REPORTED_PREFIX = re.compile(
    r"\b(?:(?:he|she|they)\s+(?:said|says|told\s+me)|"
    r"(?:sabi|sinabi|narinig\s+ko\s+kay)\s+(?:ni|ng|niya|nila))\b", re.IGNORECASE
)
GAME_CONTEXT = re.compile(
    r"\b(?:in|sa)\s+(?:the\s+)?(?:game|laro|valorant|minecraft|roblox|mlbb)\b", re.IGNORECASE
)


def detect_violent_threat(text: str) -> dict | None:
    # Scope context to a clause: an earlier denial/game must not hide a later threat.
    for clause in re.split(r"[.!?;\n]+|\b(?:pero|but)\b", text, flags=re.IGNORECASE):
        if GAME_CONTEXT.search(clause):
            continue
        for pattern in THREATS:
            for match in pattern.finditer(clause):
                prefix = clause[:match.start()].strip().rstrip('"“').rstrip()
                if NEGATED_PREFIX.search(prefix) or REPORTED_PREFIX.search(prefix):
                    continue
                return {
                    "risk_level": "CRITICAL",
                    "risk_percent": 99,
                    "flag_reason": "CRITICAL: Explicit intent to kill or seriously harm another person.",
                }
    return None
