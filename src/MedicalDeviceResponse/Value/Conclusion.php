<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

use LegitHealth\MedicalDevice\Common\Code;

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
