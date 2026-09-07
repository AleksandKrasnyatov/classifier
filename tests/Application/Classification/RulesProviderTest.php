<?php

namespace Tests\Application\Classification;

use App\Application\Classification\Rule;
use App\Application\Classification\Rules;
use App\Application\Classification\RulesProvider;
use App\Application\Classification\Then;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class RulesProviderTest extends TestCase
{
    /**
     * @test
     */
    public function givenCorrectRulesConfigWhenUseServiceThenCanGetCorrectRules(): void
    {
        $rulesProvider = new RulesProvider(__DIR__ . '/../../Fixture/Classification/test-rules.yml');

        $expected = new Rules('2026-07-23T00:00:00Z', [
            new Rule(
                'premium_payment_incident',
                'customer_tier == "premium" && tags.exists(tag, tag == "payment")',
                new Then('payments-l2', 'high', 15)
            ),
            new Rule(
                'urgent_security_ticket',
                'tags.exists(tag, tag == "account_takeover") || subject.lowerAscii().contains("hacked")',
                new Then('security-response', 'critical', 5)
            ),
            new Rule(
                'new_customer_onboarding',
                'customer_age_days < 30 && tags.exists(tag, tag == "onboarding")',
                new Then('onboarding', 'normal', 120)
            ),
            new Rule(
                'mexico_spanish_queue',
                'country == "MX" && channel in ["email", "chat"]',
                new Then('latam-es', 'normal', 240)
            ),
            new Rule(
                'global_default',
                'true',
                new Then('general', 'normal', 480)
            ),
        ]);

        $rules = $rulesProvider->getRules();

        $this->assertEquals($rules, $expected);
    }

    /**
     * @test
     */
    public function givenInvalidRouteWhenUseServiceThenExceptionIsThrown(): void
    {
        $rulesProvider = new RulesProvider('invalid_route');

        $this->expectException(RuntimeException::class);

        $rulesProvider->getRules();
    }
}
