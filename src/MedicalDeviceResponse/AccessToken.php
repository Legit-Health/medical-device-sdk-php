<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse;

use DateTimeImmutable;
use DateTimeInterface;

readonly class AccessToken
{
    public DateTimeInterface $expiresAt;

    public function __construct(
        public string $value,
        public int $expiresInMinutes
    ) {
        $this->expiresAt = new DateTimeImmutable(sprintf('+%d minutes', $expiresInMinutes));
    }
}
