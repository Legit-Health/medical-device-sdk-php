<?php

namespace LegitHealth\MedicalDevice\Response\Value;

use LegitHealth\MedicalDevice\Common\Code;

final readonly class AdditionalDataItem
{
    public function __construct(
        public float $value,
        public Code $code,
        public ?string $interpretation
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            $json['value'],
            Code::fromJson($json['code']),
            $json['interpretation'] ?? null,
        );
    }
}
