<?php

namespace LegitHealth\MedicalDevice\Common;

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
