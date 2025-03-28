<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class DetectionCode
{
    public function __construct(
        public string $code,
        public ?string $display,
        public ?string $system,
        public ?string $version
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            $json['code'],
            $json['display'],
            $json['system'],
            $json['version']
        );
    }
}
