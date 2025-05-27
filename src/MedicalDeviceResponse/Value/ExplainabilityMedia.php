<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class ExplainabilityMedia
{
    public function __construct(
        public string $title,
        public string $contentType,
        public string $data,
        public int $height,
        public int $width,
        public string $colorModel,
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            $json['title'],
            $json['contentType'],
            $json['data'],
            $json['height'],
            $json['width'],
            $json['colorModel']
        );
    }
}
