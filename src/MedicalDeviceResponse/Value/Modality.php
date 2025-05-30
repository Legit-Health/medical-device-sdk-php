<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Modality
{
    public function __construct(
        public ModalityValue $value,
        public ModalityAdditionalData $additionalData
    ) {
    }

    public static function fromJson(array $json): self
    {
        return new self(
            value: ModalityValue::from($json['value']),
            additionalData: new ModalityAdditionalData(
                aiConfidenceClinical: AdditionalDataItem::fromJson($json['additionalData']['aiConfidenceClinical']),
                aiConfidenceDermoscopic: AdditionalDataItem::fromJson($json['additionalData']['aiConfidenceDermoscopic'])
            )
        );
    }
}
