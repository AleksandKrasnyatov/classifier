<?php

declare(strict_types=1);

namespace App\Application\Classification;

use DateTimeInterface;

/** new */
final class Rules
{
    /**
     * @param string $version
     * @param Rule[] $rules
     */
    public function __construct(
        public string $version,
        public array $rules,
    ) {
    }

    public static function create(array $rules): self
    {
        return new self(
            self::parseVersion($rules['version']),
            array_map(fn (array $rule) => Rule::create($rule), $rules['rules']),
        );
    }

    private static function parseVersion(mixed $version): string
    {
        if ($version instanceof DateTimeInterface) {
            return $version->format('Y-m-d\TH:i:s\Z');
        } elseif (is_int($version)) {
            return gmdate('Y-m-d\TH:i:s\Z', $version);
        }
        return $version;
    }
}
