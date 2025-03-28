<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Modality
{
    public function __construct(
        public ModalityValue $modality,
        public ModalityAdditionalData $additionalData
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            modality: ModalityValue::from($json['modality']),
            additionalData: new ModalityAdditionalData(
                aiConfidenceClinical: $json['additionalData']['aiConfidenceClinical']['value'],
                aiConfidenceDermoscopic: $json['additionalData']['aiConfidenceDermoscopic']['value'],
            )
        );
    }
}
