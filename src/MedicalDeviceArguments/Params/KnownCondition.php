<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

use LegitHealth\MedicalDevice\Common\Code;

readonly class KnownCondition
{
    public function __construct(
        public Code $conclusion
    ) {}


    public function asArray(): array
    {
        return [
            'conclusion' => $this->conclusion->asArray()
        ];
    }
}
