<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class EvolutionItem
{
    public function __construct(
        public string $itemCode,
        public Code $code,
        public float $value,
        public ?string $interpretation,
        public ?array $additionalData
    ) {}

    public static function fromJson(string $itemCode, array $json): self
    {
        return new self(
            $itemCode,
            Code::fromJson($json['code']),
            $json['value'],
            $json['interpretation'] ?? null,
            $json['additionalData'] ?? null
        );
    }
}
