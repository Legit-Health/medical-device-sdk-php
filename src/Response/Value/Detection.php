<?php

namespace LegitHealth\MedicalDevice\Response\Value;

use LegitHealth\MedicalDevice\Common\Code;

final readonly class Detection
{
    public function __construct(
        public float $confidence,
        public Code $code,
        public Box $box
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            $json['confidence'],
            Code::fromJson($json['code']),
            new Box(
                $json['box']['x1'],
                $json['box']['y1'],
                $json['box']['x2'],
                $json['box']['y2']
            )
        );
    }
}
