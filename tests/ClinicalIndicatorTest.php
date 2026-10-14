<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\Response\Value\ClinicalIndicator;
use PHPUnit\Framework\TestCase;

class ClinicalIndicatorTest extends TestCase
{
    private const array TIERS = [
        'hasCondition' => 99.9,
        'pigmentedLesion' => 2.1,
        'malignancy' => 1.2,
        'urgentReferral' => 20.0,
        'highPriorityReferral' => 70.0,
    ];

    public function testReadsTheReferralProbability(): void
    {
        $clinicalIndicator = ClinicalIndicator::fromJson([...self::TIERS, 'referral' => 85.0]);

        $this->assertSame(85.0, $clinicalIndicator->referral);
        $this->assertSame(20.0, $clinicalIndicator->urgentReferral);
        $this->assertSame(70.0, $clinicalIndicator->highPriorityReferral);
    }

    public function testReadsAMissingReferralAsNull(): void
    {
        $this->assertNull(ClinicalIndicator::fromJson(self::TIERS)->referral);
    }

    public function testReadsANullReferralAsNull(): void
    {
        $this->assertNull(ClinicalIndicator::fromJson([...self::TIERS, 'referral' => null])->referral);
    }
}
