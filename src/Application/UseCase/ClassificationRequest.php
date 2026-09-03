<?php

declare(strict_types=1);

namespace App\Application\UseCase;

/** new */
final readonly class ClassificationRequest
{
    public function __construct(
        public string $channel,
        public string $customerTier,
        public string $country,
        public string $subject,
        public array $tags,
        public int $customerAgeDays,
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
