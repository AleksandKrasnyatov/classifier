<?php

declare(strict_types=1);

namespace App\Application\Classification;

use Cel\CommonExpressionLanguage;

class Checker
{
    public function __construct(
        private CommonExpressionLanguage $cle
    ) {
    }

    public function check(Rule $rule, array $context): bool
    {
        $compiledExpr = $this->cle->parseString($rule->when);
        $receipt = $this->cle->run($compiledExpr, $context);

        return $receipt->result->getRawValue() === true;
    }
}
