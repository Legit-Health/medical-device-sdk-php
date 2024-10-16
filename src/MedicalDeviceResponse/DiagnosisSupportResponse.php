<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse;

use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\{
    ClinicalIndicators,
    Conclusion,
    ConclusionCoding,
    FailedMedia,
    ImagingStudySeriesInstance,
    MediaValidity,
    Media,
    PerformanceIndicators
};
use DateTimeImmutable;

final readonly class DiagnosisSupportResponse
{
    /**
     * @param Conclusion[] $conclusions
     * @param ImagingStudySeriesInstance[] $imagingStudySeries
     */
    public function __construct(
        public ClinicalIndicators $clinicalIndicators,
        public PerformanceIndicators $performanceIndicators,
        public array $conclusions,
        public array $imagingStudySeries,
        public float $analysisDuration,
        public DateTimeImmutable $effectiveDateTime
    ) {
    }

    public static function createFromJson(array $json): self
    {
        $clinicalIndicators = new ClinicalIndicators(
            hasCondition: $json['clinicalIndicators']['hasCondition'],
            pigmentedLesion: $json['clinicalIndicators']['pigmentedLesion'],
            malignancy: $json['clinicalIndicators']['malignancy'],
            urgentReferral: $json['clinicalIndicators']['urgentReferral'],
            highPriorityReferral: $json['clinicalIndicators']['highPriorityReferral'],
        );


        $metrics = new PerformanceIndicators(
            $json['performanceIndicators']['sensitivity'],
            $json['performanceIndicators']['specificity'],
            $json['performanceIndicators']['entropy'],
            $json['performanceIndicators']['category'],
            $json['performanceIndicators']['type'],
        );

        $finalConclusions = [];
        if (isset($json['conclusions'])) {
            foreach ($json['conclusions'] as $singleConclusion) {
                $finalConclusions[] = new Conclusion(
                    $singleConclusion['probability'],
                    new ConclusionCoding(
                        $singleConclusion['coding']['code'],
                        $singleConclusion['coding']['display'],
                        $singleConclusion['coding']['system'],
                        $singleConclusion['coding']['systemAlias']
                    )
                );
            }
        }


        $imagingStudySeries = [];
        foreach ($json['imagingStudySeries'] as $imagingStudySeriesRecord) {
            $conclusions = [];

            foreach (($imagingStudySeriesRecord['conclusions'] ?? []) as $singleConclusion) {
                $conclusions[] = new Conclusion(
                    $singleConclusion['probability'],
                    new ConclusionCoding(
                        $singleConclusion['coding']['code'],
                        $singleConclusion['coding']['display'],
                        $singleConclusion['coding']['system'],
                        $singleConclusion['coding']['systemAlias']
                    )
                );
            }

            $imagingStudySeries[] = new ImagingStudySeriesInstance(
                $conclusions,
                new Media(
                    $imagingStudySeriesRecord['media']['modality'],
                    MediaValidity::fromJson($imagingStudySeriesRecord['media']['validity'])
                ),
                new PerformanceIndicators(
                    $imagingStudySeriesRecord['performanceIndicators']['sensitivity'],
                    $imagingStudySeriesRecord['performanceIndicators']['specificity'],
                    $imagingStudySeriesRecord['performanceIndicators']['entropy'],
                    $imagingStudySeriesRecord['performanceIndicators']['category'],
                    $imagingStudySeriesRecord['performanceIndicators']['type'],
                ),
                new ClinicalIndicators(
                    hasCondition: $imagingStudySeriesRecord['clinicalIndicators']['hasCondition'],
                    pigmentedLesion: $imagingStudySeriesRecord['clinicalIndicators']['pigmentedLesion'],
                    malignancy: $imagingStudySeriesRecord['clinicalIndicators']['malignancy'],
                    urgentReferral: $imagingStudySeriesRecord['clinicalIndicators']['urgentReferral'],
                    highPriorityReferral: $imagingStudySeriesRecord['clinicalIndicators']['highPriorityReferral'],
                )
            );
        }

        return new self(
            $clinicalIndicators,
            $metrics,
            $finalConclusions,
            $imagingStudySeries,
            \floatval(str_replace(' secs', '', $json['analysisDuration'] ?? '')),
            new DateTimeImmutable($json['effectiveDateTime'])
        );
    }

    /**
     * @return Conclusion[]
     */
    public function getPossibleConclusions(): array
    {
        return array_filter($this->conclusions, fn ($conclusion) => $conclusion->isPossible());
    }

    /**
     * @return FailedMedia[]
     */
    public function getIndexOfFailedMedias(): array
    {
        $indexes = [];
        foreach ($this->imagingStudySeries as $index => $imagingStudySeriesInstance) {
            if (!$imagingStudySeriesInstance->media->validity->isValid) {
                $indexes[] = new FailedMedia(
                    $index,
                    $imagingStudySeriesInstance->media->validity->getFailedValidityMetric()
                );
            }
        }
        return $indexes;
    }
}
