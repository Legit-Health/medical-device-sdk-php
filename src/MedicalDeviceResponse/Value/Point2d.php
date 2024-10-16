<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Point2d
{
    public function __construct(
        public float $x,
        public float $y,
    ) {
    }
}
