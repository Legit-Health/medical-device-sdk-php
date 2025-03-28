<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Domain
{
    public function __construct(
        public bool $isDermatological,
        public AiConfidence $aiConfidence
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            isDermatological: $json['isDermatological'],
            aiConfidence: new AiConfidence(
                $json['additionalData']['aiConfidence']['value'],
                Code::fromJson($json['additionalData']['aiConfidence']['code'])
            )
        );
    }
}
