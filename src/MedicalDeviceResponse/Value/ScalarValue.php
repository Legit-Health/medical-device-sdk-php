<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

use LegitHealth\MedicalDevice\Common\Code;

final readonly class ScalarValue
{
    public function __construct(
        public float $value,
        public Code $code
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            $json['value'],
            Code::fromJson($json['code'])
        );
    }
}
