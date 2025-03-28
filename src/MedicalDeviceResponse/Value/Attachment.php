<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Attachment
{
    public function __construct(
        public string $code,
        public string $title,
        public string $contentType,
        public string $data,
        public int $height,
        public int $width,
        public string $colorModel
    ) {}

    public static function fromJson(string $code, array $json): self
    {
        return new self(
            $code,
            $json['title'],
            $json['contentType'],
            $json['data'],
            $json['height'],
            $json['width'],
            $json['data'],
            $json['colorModel']
        );
    }
}
