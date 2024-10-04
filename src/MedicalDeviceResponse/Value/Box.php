<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Box
{
    public function __construct(
        public Point2d $p1,
        public Point2d $p2,
    ) {
    }
}
