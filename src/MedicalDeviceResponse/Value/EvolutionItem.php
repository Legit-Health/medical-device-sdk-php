<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

use LegitHealth\MedicalDevice\Common\Code;

final readonly class EvolutionItem
{
    /**
     * @param AdditionalDataItem[]|null $additionalData
     */
    public function __construct(
        public string $itemCode,
        public Code $code,
        public float $value,
        public ?string $interpretation,
        public ?array $additionalData
    ) {}

    public static function fromJson(string $itemCode, array $json): self
    {
        $additionalData = null;
        if (isset($json['additionalData'])) {
            $additionalData = [];
            foreach ($json['additionalData'] as $additionalDataItemCode => $additionalDataItem) {
                $additionalData[$additionalDataItemCode] = AdditionalDataItem::fromJson($additionalDataItem);
            }
        }

        return new self(
            $itemCode,
            Code::fromJson($json['code']),
            (float)$json['value'],
            $json['interpretation'] ?? null,
            $additionalData
        );
    }
}
