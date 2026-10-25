<?php

namespace LegitHealth\MedicalDevice\Tests;

use DateTimeImmutable;
use LegitHealth\MedicalDevice\Response\AccessToken;
use PHPUnit\Framework\TestCase;

class AccessTokenTest extends TestCase
{
    private string $defaultTimezone;

    public function setUp(): void
    {
        $this->defaultTimezone = date_default_timezone_get();
        date_default_timezone_set('Europe/Madrid');
    }

    public function tearDown(): void
    {
        date_default_timezone_set($this->defaultTimezone);
    }

    public function testExpiresAfterTheGivenMinutesOfRealTime(): void
    {
        $issuedAt = new DateTimeImmutable('2026-06-01 10:00:00');

        $accessToken = new AccessToken('token', 'Bearer token', 30, $issuedAt);

        $this->assertSame($issuedAt->getTimestamp() + 30 * 60, $accessToken->expiresAt->getTimestamp());
    }

    /**
     * Summer time ended in Madrid on 2026-10-25 at 03:00. A 24-hour token issued the day before
     * expires at 10:26 on the clock, not 11:26. With 11:26 the client kept sending a token the
     * device had already expired, and got a 401 for one hour.
     */
    public function testExpiresAfterTheGivenMinutesOfRealTimeWhenSummerTimeEnds(): void
    {
        $issuedAt = new DateTimeImmutable('2026-10-24 11:26:00');

        $accessToken = new AccessToken('token', 'Bearer token', 1440, $issuedAt);

        $this->assertSame($issuedAt->getTimestamp() + 86400, $accessToken->expiresAt->getTimestamp());
        $this->assertSame('2026-10-25 10:26:00', $accessToken->expiresAt->format('Y-m-d H:i:s'));
    }

    public function testExpiresAfterTheGivenMinutesOfRealTimeWhenSummerTimeStarts(): void
    {
        $issuedAt = new DateTimeImmutable('2027-03-27 11:26:00');

        $accessToken = new AccessToken('token', 'Bearer token', 1440, $issuedAt);

        $this->assertSame($issuedAt->getTimestamp() + 86400, $accessToken->expiresAt->getTimestamp());
        $this->assertSame('2027-03-28 12:26:00', $accessToken->expiresAt->format('Y-m-d H:i:s'));
    }
}
