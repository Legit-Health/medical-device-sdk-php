<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class ClinicalIndicator
{
    /**
     * @param ?float $referral Probability that the case warrants urgent or high-priority referral.
     *                         A condition flagged in both tiers counts once, so it is neither the
     *                         sum nor the larger of $urgentReferral and $highPriorityReferral.
     *                         Null when the device response does not include it.
     */
    public function __construct(
        public float $hasCondition,
        public float $pigmentedLesion,
        public float $malignancy,
        public float $urgentReferral,
        public float $highPriorityReferral,
        public ?float $referral = null
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            hasCondition: $json['hasCondition'],
            pigmentedLesion: $json['pigmentedLesion'],
            malignancy: $json['malignancy'],
            urgentReferral: $json['urgentReferral'],
            highPriorityReferral: $json['highPriorityReferral'],
            referral: $json['referral'] ?? null,
        );
    }
}
