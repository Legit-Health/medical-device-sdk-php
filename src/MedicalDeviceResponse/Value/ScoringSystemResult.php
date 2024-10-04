<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\ScoringSystemCode;

final readonly class ScoringSystemResult
{
    /**
     * @param array<string,FacetScore> $facetScores
     * @param Attachment[] $attachments
     * @param Detection[] $detections
     * */
    public function __construct(
        public ScoringSystemCode $scoringSystemCode,
        public ScoringSystemScore $score,
        public array $facetScores,
        public array $attachments,
        public array $detections
    ) {
    }

    public function getFacetScore(string $facetCode): FacetScore
    {
        return $this->facetScores[$facetCode];
    }

    /**
     * @return FacetScore[]
     */
    public function getFacetScores(): array
    {
        return array_values($this->facetScores);
    }
}
