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
        public int $expiresInMinutes,
        DateTimeImmutable $issuedAt = new DateTimeImmutable()
    ) {
        // Add real seconds, not clock time: `modify('+N minutes')` adds clock time in the default
        // timezone, so a token that lives across the end of summer time came out one hour too late.
        $this->expiresAt = $issuedAt->setTimestamp($issuedAt->getTimestamp() + $expiresInMinutes * 60);
    }
}
