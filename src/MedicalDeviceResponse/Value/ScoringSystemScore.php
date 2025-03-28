<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class ScoringSystemScore
{
    public function __construct(
        public float $value,
        public Interpretation $interpretation,
        public ?GlobalScoreContribution $globalScore
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            value: $json['value'],
            interpretation: new Interpretation($json['interpretation']['category'], Intensity::from($json['interpretation']['intensity'])),
            globalScore: GlobalScoreContribution::fromJson(
                $json['additionalData']['globalScoreContribution'] ?? null
            )
        );
    }
}
