<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Code
{
    public function __construct(
        public ?string $coding,
        public string $text
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            $json['coding'],
            $json['text'],
        );
    }
}
