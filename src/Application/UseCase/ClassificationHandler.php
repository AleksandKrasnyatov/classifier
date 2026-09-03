<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Classification\RulesProvider;
use Cel\CommonExpressionLanguage;
use DomainException;

/** new */
final readonly class ClassificationHandler
{
    public function __construct(
        private RulesProvider $rulesProvider,
        private CommonExpressionLanguage $cle
    ) {
    }

    public function __invoke(ClassificationRequest $classificationRequest): ClassificationResponse
    {
        $context = $classificationRequest->toArray();
        $rules = $this->rulesProvider->getRules();
        foreach ($rules->rules as $rule) {
            $compiledExpr = $this->cle->parseString($rule->when);
            $receipt = $this->cle->run($compiledExpr, $context);
            if ($receipt->result->getRawValue() === true) {
                return new ClassificationResponse(
                    queue: $rule->then->queue,
                    priority: $rule->then->priority,
                    responseTargetMinutes: $rule->then->response_target_minutes,
                    ruleId: $rule->id,
                    rulesVersion: $rules->version
                );
            }
        }

        throw new DomainException("No matching routing rule found for the request.");
    }
}
