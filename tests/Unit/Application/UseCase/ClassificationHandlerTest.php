<?php

namespace Tests\Unit\Application\UseCase;

use App\Application\Classification\Rule;
use App\Application\Classification\Rules;
use App\Application\Classification\RulesProvider;
use App\Application\Classification\Then;
use App\Application\UseCase\ClassificationHandler;
use App\Application\UseCase\ClassificationRequest;
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
//        $rulesProvider = $this->createStub(RulesProvider::class)->method('getRules')->willReturn([
//            new Rule(
//                'premium_payment_incident',
//                'customer_tier == "premium" && tags.exists(tag, tag == "payment")',
//                new Then('payments-l2', 'high', 15)
//            ),
//        ]);
//        $cle = $this->createStub(CommonExpressionLanguage::class)
//            ->method('run')
//            ->willReturn();

        //todo проверка ответа при запросе, на моках в конструкторе хендлера

//        $handler = new ClassificationHandler($rulesProvider, $cle);
    }

    /**
     * @test
     */
    public function givenClassificationRequestWhenDoesNotHaveFitClassificationRuleThenExceptionIsThrown()
    {
        $rulesProvider = $this->createStub(RulesProvider::class);
        $rulesProvider->method('getRules')->willReturn(new Rules('2026-07-23T00:00:00Z', []));
        $cle = new CommonExpressionLanguage;
        $handler = new ClassificationHandler($rulesProvider, $cle);

        $request = new ClassificationRequest('', '', '', '', [], 0);

        $this->expectException(DomainException::class);

        $handler($request);
    }
}
