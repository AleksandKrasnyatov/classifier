<?php

declare(strict_types=1);

namespace App\Application\Classification;

/** new */
final class Rule
{
    public function __construct(
        public string $id,
        public string $when,
        public Then $then,
    ) {
    }

    public static function create(array $rule): self
    {
        return new self(
            $rule['id'],
            $rule['when'],
            Then::create($rule['then'])
        );
    }
}
