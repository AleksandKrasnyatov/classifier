<?php

declare(strict_types=1);

namespace Functional\Application\Infrastructure\Http\Classification;

use App\Application\Actions\ActionPayload;
use Exception;
use Tests\TestCase;

class CheckActionTest extends TestCase
{
    /**
     * @test
     * @dataProvider incorrectCases
     * @throws Exception
     */
    public function givenRequestWithIncorrectStructureWhenRequestClassificationThenSeeExceptionCode(array $in, int $code): void
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('POST', '/')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody($in);

        $response = $app->handle($request);

        $this->assertEquals($code, $response->getStatusCode());

    }

    /**
     * @test
     * @dataProvider successCases
     * @throws Exception
     */
    public function givenRequestWithCorrectStructureWhenRequestClassificationThenSeeExpectedResponse(array $in, array $out): void
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('POST', '/')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody($in);

        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $expectedPayload = new ActionPayload(200, $out);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);
        $this->assertEquals($serializedPayload, $payload);
    }

    public function successCases(): array
    {
        return [
            'success_with_rule' => [
                'in' => [
                    "channel" => "email",
                    "customer_tier" => "premium",
                    "country" => "MX",
                    "subject" => "Payment was charged twice",
                    "tags" => ["payment", "duplicate_charge"],
                    "customer_age_days" => 420
                ],
                'out' => [
                    "queue" => "payments-l2",
                    "priority" => "high",
                    "response_target_minutes" => 15,
                    "rule_id" => "premium_payment_incident",
                    "rules_version" => "2026-07-23T00:00:00Z"
                ],
            ],
            'success_with_default_rule' => [
                'in' => [
                    "channel" => "",
                    "customer_tier" => "",
                    "country" => "",
                    "subject" => "",
                    "tags" => [],
                    "customer_age_days" => 0
                ],
                'out' => [
                    "queue" => "general",
                    "priority" => "normal",
                    "response_target_minutes" => 480,
                    "rule_id" => "global_default",
                    "rules_version" => "2026-07-23T00:00:00Z"
                ]
            ],
        ];
    }

    public function incorrectCases(): array
    {
        return [
            'empty' => [
                'in' => [],
                'code' => 500
            ],
            'incorrectFields' => [
                'in' => ['something' => 'strange'],
                'code' => 500
            ]
        ];
    }
}