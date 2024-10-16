<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class ConclusionCoding
{
    public function __construct(
        public string $code,
        public string $display,
        public string $system,
        public string $systemAlias
    ) {
    }
}
