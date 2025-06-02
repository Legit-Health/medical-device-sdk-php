<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class Media
{
    public function __construct(
        public string $modality,
        public MediaValidity $validity
    ) {}
}
