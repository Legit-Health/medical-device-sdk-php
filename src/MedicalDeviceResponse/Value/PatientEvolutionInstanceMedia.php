<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class PatientEvolutionInstanceMedia
{
    /**
     * @param array<string,Attachment> $attachment
     * @param Detection[] $detection
     * */
    public function __construct(
        public ?array $attachment,
        public array $detection
    ) {}
}
