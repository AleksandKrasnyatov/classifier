<?php

declare(strict_types=1);

namespace App\Application\Classification;

final class Then
{
    public function __construct(
        public string $queue,
        public string $priority,
        public int $response_target_minutes,
    ) {
    }

    public static function create(array $then): self
    {
        return new self(
            $then['queue'],
            $then['priority'],
            $then['response_target_minutes'],
        );
    }
}
