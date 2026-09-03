<?php

declare(strict_types=1);

namespace App\Application\UseCase;

final class ClassificationResponse
{
    public function __construct(
        public readonly string $queue,
        public readonly string $priority,
        public readonly int $responseTargetMinutes,
        public readonly string $ruleId,
        public readonly string $rulesVersion,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            queue: $data['queue'] ?? '',
            priority: $data['priority'] ?? '',
            responseTargetMinutes: (int)($data['response_target_minutes'] ?? 0),
            ruleId: $data['rule_id'] ?? '',
            rulesVersion: $data['rules_version'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'queue' => $this->queue,
            'priority' => $this->priority,
            'response_target_minutes' => $this->responseTargetMinutes,
            'rule_id' => $this->ruleId,
            'rules_version' => $this->rulesVersion,
        ];
    }
}
