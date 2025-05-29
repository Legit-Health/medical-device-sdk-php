<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Domain
{
    public function __construct(
        public bool $isDermatological,
        public DomainAdditionalData $additionalData
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            isDermatological: $json['isDermatological'],
            additionalData: new DomainAdditionalData(AdditionalDataItem::fromJson($json['additionalData']['aiConfidence']))
        );
    }
}
