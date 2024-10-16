<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Media
{
    public function __construct(
        public string $modality,
        public MediaValidity $validity
    ) {
    }
}
