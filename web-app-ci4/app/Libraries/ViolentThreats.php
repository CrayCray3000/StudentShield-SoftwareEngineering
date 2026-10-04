<?php

namespace App\Libraries;

/** Deterministic safety floor mirrored by ml_service/violent_threats.py. */
final class ViolentThreats
{
    public static function detect(string $text): ?array
    {
        $patterns = [
            '~\b(?:papatayin|patayin|sasaksakin|saksakin|babarilin|barilin)\s+'
            . '(?:(?:na|lang)\s+)*(?:(?:ko|namin|natin)\s+(?:(?:na|lang|lahat)\s+)*'
            . '(?:(?:sila|siya|kayo|ka)(?:ng)?|lahat|mga\s+(?:kaibigan|kaklase)|si\s+\w+)|kita)\b~iu',
            '~\b(?:i\s+(?:will|want\s+to|plan\s+to|intend\s+to|am\s+going\s+to)|'
            . "i['’](?:ll|m\\s+(?:going\\s+to|gonna))|i\\s+gonna)\\s+"
            . '(?:kill|murder|stab|shoot)\s+(?:you|him|her|them|everyone|everybody|'
            . 'all\s+of\s+(?:you|them)|my\s+(?:classmates?|friends?|teacher))\b~iu',
        ];

        foreach (preg_split('~[.!?;\n]+|\b(?:pero|but)\b~iu', $text) as $clause) {
            if (preg_match('~\b(?:in|sa)\s+(?:the\s+)?(?:game|laro|valorant|minecraft|roblox|mlbb)\b~iu', $clause)) {
                continue;
            }
            foreach ($patterns as $pattern) {
                preg_match_all($pattern, $clause, $matches, PREG_OFFSET_CAPTURE);
                foreach ($matches[0] as [$match, $offset]) {
                    $prefix = rtrim(trim(substr($clause, 0, $offset)), "\"“ \t");
                    $negated = preg_match('~\b(?:(?:hindi|di)(?:\s+naman)?\s+(?:totoo(?:ng)?(?:\s+na)?|(?:ko\s+)?(?:sinabi|sinasabi|sasabihin)\s+na)|(?:ayoko|huwag|wag)\s+(?:nang\s+)?sabihin\s+na|not\s+true\s+that|never\s+said)\s*$~iu', $prefix);
                    $reported = preg_match('~\b(?:(?:he|she|they)\s+(?:said|says|told\s+me)|(?:sabi|sinabi|narinig\s+ko\s+kay)\s+(?:ni|ng|niya|nila))\b~iu', $prefix);
                    if (!$negated && !$reported) {
                        return ['risk_level' => 'CRITICAL', 'risk_percent' => 99];
                    }
                }
            }
        }
        return null;
    }

    /** Apply after ML success as well as during an outage; never lower a score. */
    public static function enforce(string $text, array $result): array
    {
        $threat = self::detect($text);
        if ($threat !== null) {
            $result['risk_level'] = $threat['risk_level'];
            $result['risk_percent'] = max((float) ($result['risk_percent'] ?? 0), $threat['risk_percent']);
        }
        return $result;
    }
}
