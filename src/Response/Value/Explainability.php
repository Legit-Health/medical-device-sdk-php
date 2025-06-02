<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class Explainability
{
    public function __construct(
        public ExplainabilityMedia $heatMap
    ) {}

    public static function fromJson(?array $json): ?self
    {
        if ($json === null) {
            return $json;
        }
        return new self(
            ExplainabilityMedia::fromJson($json['heatMap'])
        );
    }
}
