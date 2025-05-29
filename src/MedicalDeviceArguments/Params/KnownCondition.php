<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

use JsonSerializable;
use LegitHealth\MedicalDevice\Common\Code;

readonly class KnownCondition implements JsonSerializable
{
    public function __construct(
        public Code $conclusion
    ) {}


    public function jsonSerialize(): mixed
    {
        return [
            'conclusion' => $this->conclusion
        ];
    }
}
