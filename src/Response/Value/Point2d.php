<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class Point2d
{
    public function __construct(
        public float $x,
        public float $y,
    ) {}
}
