<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class FailedMedia
{
    public function __construct(
        public int $index,
        public ?ValidityMetric $failedMetric
    ) {
    }
}
