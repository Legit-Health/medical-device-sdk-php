<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

use LegitHealth\MedicalDevice\Common\Code;

final readonly class EvolutionItem
{
    public function __construct(
        public string $itemCode,
        public Code $code,
        public float $value,
        public ?string $interpretation,
        public ?EvolutionItemAdditionalData $additionalData
    ) {}

    public static function fromJson(string $itemCode, array $json): self
    {
        $getAdditionalData = fn(string $key) => isset($json['additionalData'][$key])
            ? AiConfidence::fromJson($json['additionalData'][$key])
            : null;
        return new self(
            $itemCode,
            Code::fromJson($json['code']),
            (float)$json['value'],
            $json['interpretation'] ?? null,
            isset($json['additionalData']) ? new EvolutionItemAdditionalData(
                $getAdditionalData('aiConfidence'),
                $getAdditionalData('presenceProbability')
            ) : null
        );
    }
}
