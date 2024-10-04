<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class ImagingStudySeriesInstance
{
    /**
     * @param Conclusion[] $conclusions
     */
    public function __construct(
        public array $conclusions,
        public Media $media,
        public PerformanceIndicators $performanceIndicators,
        public ClinicalIndicators $clinicalIndicators,
    ) {
    }
}
