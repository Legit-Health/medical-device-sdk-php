<?php

namespace LegitHealth\MedicalDevice\Response;

use DateTimeImmutable;
use DateTimeInterface;

readonly class AccessToken
{
    public DateTimeInterface $expiresAt;

    public function __construct(
        public string $value,
        public string $tokenType,
        public int $expiresInMinutes
    ) {
        $this->expiresAt = new DateTimeImmutable(sprintf('+%d minutes', $expiresInMinutes));
    }
}
