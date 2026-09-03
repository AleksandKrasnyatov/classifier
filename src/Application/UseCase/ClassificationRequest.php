<?php

declare(strict_types=1);

namespace App\Application\UseCase;

final class ClassificationRequest
{
    public function __construct(
        public readonly string $channel,
        public readonly string $customerTier,
        public readonly string $country,
        public readonly string $subject,
        public readonly array $tags,
        public readonly int $customerAgeDays,
    ) {
    }

    /**
     * Преобразует DTO обратно в массив (с snake_case ключами)
     */
    public function toArray(): array
    {
        return [
            'channel' => $this->channel,
            'customer_tier' => $this->customerTier,
            'country' => $this->country,
            'subject' => $this->subject,
            'tags' => $this->tags,
            'customer_age_days' => $this->customerAgeDays,
        ];
    }
}
