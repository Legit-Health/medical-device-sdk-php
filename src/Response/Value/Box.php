<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class Box
{
    public function __construct(
        public float $x1,
        public float $y1,
        public float $x2,
        public float $y2
    ) {}
}
