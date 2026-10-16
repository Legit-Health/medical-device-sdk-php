<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class ImageValidationInstance
{
    public function __construct(
        public MediaValidity $mediaValidity
    ) {}
}
