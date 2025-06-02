<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class ImagingAnalysisInstance
{
    /**
     * @param Conclusion[] $conclusions
     */
    public function __construct(
        public array $conclusions,
        public MediaValidity $mediaValidity,
        public ImagingAnalysisInstancePerformanceIndicator $performanceIndicator,
        public ClinicalIndicator $clinicalIndicator
    ) {}
}
