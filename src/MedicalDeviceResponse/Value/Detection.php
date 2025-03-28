<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Detection
{
    public function __construct(
        public float $confidence,
        public DetectionCode $code,
        public Box $box
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            $json['confidence'],
            DetectionCode::fromJson($json['code']),
            new Box(
                new Point2d($json['box']['x1'], $json['box']['y1']),
                new Point2d($json['box']['x2'], $json['box']['y2']),
            )
        );
    }
}
