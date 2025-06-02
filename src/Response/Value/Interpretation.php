<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class Interpretation
{
    public function __construct(
        public string $category,
        public Intensity $intensity
    ) {}
}
