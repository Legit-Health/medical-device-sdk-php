<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class PerformanceIndicator
{
    public function __construct(
        public float $sensitivity,
        public float $specificity,
        public float $entropy
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            $json['sensitivity'],
            $json['specificity'],
            $json['entropy']
        );
    }
}
