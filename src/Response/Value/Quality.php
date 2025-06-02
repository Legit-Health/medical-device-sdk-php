<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class Quality
{
    public function __construct(
        public bool $acceptable,
        public float $score,
        public string $interpretation
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            acceptable: $json['acceptable'],
            score: \floatval($json['score']),
            interpretation: $json['interpretation']
        );
    }
}
