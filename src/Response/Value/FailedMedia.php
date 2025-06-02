<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class FailedMedia
{
    public function __construct(
        public int $index,
        public MediaValidity $mediaValidity
    ) {}
}
