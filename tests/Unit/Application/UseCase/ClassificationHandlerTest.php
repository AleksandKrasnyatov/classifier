<?php

namespace Tests\Unit\Application\UseCase;

use App\Application\Classification\Checker;
use App\Application\Classification\Rule;
use App\Application\Classification\Rules;
use App\Application\Classification\RulesProvider;
use App\Application\Classification\Then;
use App\Application\UseCase\ClassificationHandler;
use App\Application\UseCase\ClassificationRequest;
use App\Application\UseCase\ClassificationResponse;
use Cel\CommonExpressionLanguage;
use DomainException;
use PHPUnit\Framework\TestCase;

class ClassificationHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function givenClassificationRequestWhenHaveFitClassificationRulesThenSeeExpectedResponse()
    {
        $rulesProvider = $this->createStub(RulesProvider::class);
        $rulesProvider->method('getRules')->willReturn(
            new Rules('2026-07-23T00:00:00Z', [
                new Rule(
                    'premium_payment_incident',
                    'customer_tier == "premium" && tags.exists(tag, tag == "payment")',
                    new Then('payments-l2', 'high', 15)
                ),
            ])
        );
        $checker = new Checker(new CommonExpressionLanguage());

        $handler = new ClassificationHandler($rulesProvider, $checker);

        $request = new ClassificationRequest(
            'email',
            'premium',
            'MX',
            'Payment was charged twice',
            ['payment', 'duplicate_charge'],
            420
        );

        $expectedResponse = new ClassificationResponse(
            'payments-l2',
            'high',
            15,
            'premium_payment_incident',
            '2026-07-23T00:00:00Z'
        );

        $this->assertEquals($expectedResponse, $handler($request));
    }

    /**
     * @test
     */
    public function givenClassificationRequestWhenDoesNotHaveFitClassificationRuleThenExceptionIsThrown()
    {
        $rulesProvider = $this->createStub(RulesProvider::class);
        $rulesProvider->method('getRules')->willReturn(new Rules('2026-07-23T00:00:00Z', []));
        $checker = $this->createStub(Checker::class);
        $handler = new ClassificationHandler($rulesProvider, $checker);

        $request = new ClassificationRequest('', '', '', '', [], 0);

        $this->expectException(DomainException::class);

        $handler($request);
    }
}
