<?php

declare(strict_types=1);

namespace App\Application\Classification;

use RuntimeException;
use Symfony\Component\Yaml\Yaml;

/** new */
class RulesProvider
{
    private ?Rules $rules = null;

    public function __construct(
        private readonly string $yamlFilePath
    ) {
    }

    public function getRules(): Rules
    {
        if ($this->rules === null) {
            $yamlRawRules = $this->getRawRules();
            $this->rules = Rules::create($yamlRawRules);
        }
        return $this->rules;
    }

    private function getRawRules(): array
    {
        if (!file_exists($this->yamlFilePath)) {
            throw new RuntimeException("YAML rules file not found: {$this->yamlFilePath}");
        }

        $yamlContent = file_get_contents($this->yamlFilePath);
        return Yaml::parse($yamlContent);
    }
}
