<?php
// Run as: php spark decrypt:vents ../vent_logs.sql
// Outputs: ../ml_service/data/real_vents.csv

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DecryptVents extends BaseCommand
{
    protected $group       = 'StudentShield';
    protected $name        = 'decrypt:vents';
    protected $description = 'Decrypt vent_logs SQL dump and export labeled CSV for ML training.';
    protected $usage       = 'decrypt:vents [sql_file]';

    public function run(array $params)
    {
        $sqlFile    = $params[0] ?? '../vent_logs.sql';
        $outputFile = '../ml_service/data/real_vents.csv';

        if (!file_exists($sqlFile)) {
            CLI::error("File not found: $sqlFile");
            return;
        }

        $encrypter = \Config\Services::encrypter();
        $sql       = file_get_contents($sqlFile);

        // Extract INSERT rows
        $rowPattern = '/\((\d+),\s*\d+,\s*\'((?:[^\'\\\\]|\\\\.)*)\''
                    . ',\s*(?:NULL|\'((?:[^\'\\\\]|\\\\.)*)\')' 
                    . ',\s*\'?([\d.]+)\'?'
                    . ',\s*\'?(\w+)\'?/';

        preg_match_all($rowPattern, $sql, $matches, PREG_SET_ORDER);
        CLI::write("Found " . count($matches) . " rows in SQL.");

        $results    = [];
        $failed     = 0;
        $tooShort   = 0;
        $labelMap   = ['LOW' => 0, 'MEDIUM' => 0, 'HIGH' => 1, 'CRITICAL' => 1];

        foreach ($matches as $m) {
            $id         = $m[1];
            $rawText    = str_replace(["\\'", "\\\\"], ["'", "\\"], $m[2]);
            $emotions   = $m[3] ?? '';
            $riskPct    = (float) $m[4];
            $riskLevel  = strtoupper($m[5]);

            if (!isset($labelMap[$riskLevel])) continue;

            // Check if hex-encoded (encrypted) or plaintext
            $isHex = (bool) preg_match('/^[0-9a-fA-F]{32,}$/', $rawText);

            if ($isHex) {
                try {
                    $rawBin = hex2bin($rawText);
                    $text   = $encrypter->decrypt($rawBin);
                } catch (\Exception $e) {
                    $failed++;
                    if ($failed <= 5) {
                        CLI::write("[WARN] ID=$id decrypt failed: {$e->getMessage()}");
                    }
                    continue;
                }
            } else {
                $text = $rawText;  // Already plaintext
            }

            $text = trim($text);
            $wordCount = str_word_count($text);
            if ($wordCount < 4) {
                $tooShort++;
                continue;
            }

            $results[] = [
                'text'         => str_replace(["\r\n", "\n", "\r", ","], [" ", " ", " ", " "], $text),
                'label'        => $labelMap[$riskLevel],
                'risk_level'   => $riskLevel,
                'risk_percent' => $riskPct,
                'emotions'     => $emotions,
                'source'       => 'real_db',
            ];
        }

        CLI::write("\nDecrypted:   " . count($results));
        CLI::write("Failed:      $failed");
        CLI::write("Too short:   $tooShort");

        // Distribution
        $dist = array_count_values(array_column($results, 'risk_level'));
        CLI::write("\nRisk distribution:");
        foreach (['LOW','MEDIUM','HIGH','CRITICAL'] as $lvl) {
            CLI::write("  $lvl: " . ($dist[$lvl] ?? 0));
        }

        if (empty($results)) {
            CLI::error("No results — check encryption key or SQL format.");
            return;
        }

        // Write CSV
        @mkdir(dirname($outputFile), 0755, true);
        $fp = fopen($outputFile, 'w');
        fputcsv($fp, ['text','label','risk_level','risk_percent','emotions','source']);
        foreach ($results as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);

        CLI::write("\nSaved: $outputFile");
        CLI::write("Next: run python ml_service/merge_real_into_training.py");
    }
}
