<?php

namespace LegitHealth\MedicalDevice\Response\Value;

use LegitHealth\MedicalDevice\Common\Code;

final readonly class GlobalScoreContribution
{
    public function __construct(
        public float $value,
        public Code $code
    ) {}

    public static function fromJson(?array $json): ?self
    {
        if ($json === null) {
            return null;
        }
        return new self(
            $json['value'],
            Code::fromJson($json['code'])
        );
    }
}
