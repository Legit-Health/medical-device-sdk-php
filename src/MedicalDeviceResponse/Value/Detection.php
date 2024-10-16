<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Detection
{
    public function __construct(
        public float $confidence,
        public DetectionLabel $label,
        public Box $box
    ) {
    }

    public static function fromJson(array $json): self
    {
        return new self(
            $json['confidence'],
            DetectionLabel::from($json['label']),
            new Box(
                new Point2d($json['box']['x1'], $json['box']['y1']),
                new Point2d($json['box']['x2'], $json['box']['y2']),
            )
        );
    }
}
