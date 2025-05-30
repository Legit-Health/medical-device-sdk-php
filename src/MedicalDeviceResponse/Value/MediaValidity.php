<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class MediaValidity
{
    public function __construct(
        public Quality $quality,
        public Domain $domain,
        public Modality $modality,
        public bool $isValid
    ) {
    }

    public static function fromJson(array $json): self
    {
        return new self(
            Quality::fromJson($json['quality']),
            Domain::fromJson($json['domain']),
            Modality::fromJson($json['modality']),
            $json['isValid']
        );
    }
}
