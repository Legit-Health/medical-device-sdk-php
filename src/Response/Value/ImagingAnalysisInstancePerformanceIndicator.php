<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class ImagingAnalysisInstancePerformanceIndicator
{
    public function __construct(
        public float $entropy
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            $json['entropy']
        );
    }
}
