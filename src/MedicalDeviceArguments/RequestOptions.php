<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments;

readonly class RequestOptions
{
    public function __construct(
        public ?int $timeout = null
    ) {
    }

    public function asArray(): array
    {
        return [
            'timeout' => $this->timeout
        ];
    }
}
