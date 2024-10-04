<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Attachment
{
    public function __construct(
        public string $title,
        public string $contentType,
        public string $data,
        public int $height,
        public int $width
    ) {
    }
}
