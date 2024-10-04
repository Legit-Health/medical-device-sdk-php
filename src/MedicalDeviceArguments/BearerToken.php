<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments;

readonly class BearerToken
{
    public function __construct(
        public string $value
    ) {
    }

    public function asAuthorizationHeader(): string
    {
        return sprintf('Bearer %s', $this->value);
    }
}
