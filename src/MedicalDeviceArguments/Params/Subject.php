<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class Subject
{
    public function __construct(
        public string $reference
    ) {
    }

    public function toArray(): array
    {
        return [
            'reference' => $this->reference
        ];
    }
}
