<?php

namespace LegitHealth\MedicalDevice\Arguments;

readonly class RequestOptions
{
    public function __construct(
        public ?int $timeout = null
    ) {}

    public function asArray(): array
    {
        return [
            'timeout' => $this->timeout
        ];
    }
}
