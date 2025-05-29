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
        public ?array $additionalData
    ) {}

    public static function fromJson(string $itemCode, array $json): self
    {
        $getAdditionalDataItem = fn(string $key) => isset($json['additionalData'][$key])
            ? AdditionalDataItem::fromJson($json['additionalData'][$key])
            : null;

        return new self(
            $itemCode,
            Code::fromJson($json['code']),
            (float)$json['value'],
            $json['interpretation'] ?? null,
            isset($json['additionalData']) ? array_filter([
                'aiConfidence' => $getAdditionalDataItem('aiConfidence'),
                'presenceProbability' => $getAdditionalDataItem('presenceProbability'),
                'inflammatoryLesionCount' => $getAdditionalDataItem('inflammatoryLesionCount'),
                'whealsCount' => $getAdditionalDataItem('whealsCount')
            ], fn($item) => $item !== null) : null
        );
    }
}
