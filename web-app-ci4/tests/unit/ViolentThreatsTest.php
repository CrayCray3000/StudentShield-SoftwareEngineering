<?php

namespace Tests\Unit;

use App\Controllers\VentController;
use App\Libraries\ViolentThreats;
use CodeIgniter\Test\CIUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;

final class ViolentThreatsTest extends CIUnitTestCase
{
    public static function threatCases(): iterable
    {
        $path = dirname(__DIR__, 3) . '/ml_service/tests/fixtures/violent_threats.json';
        foreach (json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR) as $index => $case) {
            yield "case {$index}" => [$case['text'], $case['critical']];
        }
    }

    #[DataProvider('threatCases')]
    public function testThreatDetectionDistinguishesIntentFromContext(string $text, bool $critical): void
    {
        $result = ViolentThreats::detect($text);
        $this->assertSame($critical, $result !== null);
        if ($critical) {
            $this->assertSame('CRITICAL', $result['risk_level']);
            $this->assertGreaterThanOrEqual(99, $result['risk_percent']);
            // Even a successful LOW model response must not bypass the safety net.
            $this->assertSame('CRITICAL', ViolentThreats::enforce($text, [
                'risk_level' => 'LOW', 'risk_percent' => 1,
            ])['risk_level']);
            // The actual outage path must classify the same threat before persistence.
            $controller = new VentController();
            $fallback = new ReflectionMethod($controller, 'localKeywordFallback');
            $this->assertSame('CRITICAL', $fallback->invoke($controller, $text)['risk_level']);
        }
    }

    public function testSafetyFloorPreservesAnAlreadyHigherScore(): void
    {
        $result = ViolentThreats::enforce('Papatayin ko sila.', [
            'risk_level' => 'CRITICAL', 'risk_percent' => 100, 'trajectory' => 'ESCALATING',
        ]);
        $this->assertSame(100.0, $result['risk_percent']);
        $this->assertSame('ESCALATING', $result['trajectory']);
    }

    public function testNonThreatDoesNotOverrideMlClassification(): void
    {
        $ml = ['risk_level' => 'MEDIUM', 'risk_percent' => 50];
        $this->assertSame($ml, ViolentThreats::enforce('I hate them.', $ml));
    }
}
