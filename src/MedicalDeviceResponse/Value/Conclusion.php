<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final class Conclusion
{
    public function __construct(
        public readonly float $probability,
        public readonly Code $code,
        public readonly ?Explainability $explainability = null
    ) {}

    public bool $isPossible {
        get => $this->probability > 0;
    }
}
