<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class CodingItem
{
    public function __construct(
        public string $code,
        public string $display,
        public string $system,
        public string $systemDisplay
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            $json['code'],
            $json['display'],
            $json['system'],
            $json['systemDisplay']
        );
    }
}
