<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class KnownCondition
{
    public function __construct(
        public string $code,
        public string $display,
        public string $system,
        public string $systemAlias
    ) {}

    public static function fromIcd11(string $code, string $display): self
    {
        return new self($code, $display, 'https://icd.who.int', 'ICD-11');
    }

    public function toArray(): array
    {
        return [
            'conclusion' => [
                'code' => $this->code,
                'display' => $this->display,
                'system' => $this->system,
                'systemAlias' => $this->systemAlias
            ]
        ];
    }
}
