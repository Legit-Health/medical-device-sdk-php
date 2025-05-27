<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse;

use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\{
    ClinicalIndicator,
    Code,
    Conclusion,
    ConclusionCode,
    Explainability,
    FailedMedia,
    ImagingAnalysisInstance,
    ImagingAnalysisInstancePerformanceIndicator,
    MediaValidity,
    Media,
    PerformanceIndicator
};
use DateTimeImmutable;

final readonly class DiagnosisSupportResponse
{
    /**
     * @param Conclusion[] $conclusions
     * @param ImagingAnalysisInstance[] $imagingAnalysis
     */
    public function __construct(
        public string $resourceType,
        public string $status,
        public ClinicalIndicator $clinicalIndicator,
        public PerformanceIndicator $performanceIndicator,
        public array $conclusions,
        public array $imagingAnalysis,
        public float $analysisDuration,
        public DateTimeImmutable $issued
    ) {}

    public static function createFromJson(array $json): self
    {
        $clinicalIndicator = ClinicalIndicator::fromJson($json['clinicalIndicator']);
        $performanceIndicator = PerformanceIndicator::fromJson($json['performanceIndicator']);

        $finalConclusions = [];
        if (isset($json['conclusion'])) {
            foreach ($json['conclusion'] as $singleConclusion) {
                $finalConclusions[] = new Conclusion(
                    $singleConclusion['probability'],
                    Code::fromJson($singleConclusion['code'])
                );
            }
        }


        $imagingAnalysis = [];
        foreach ($json['imagingAnalysis'] as $imagingAnalysisRecord) {
            $conclusions = [];

            foreach (($imagingAnalysisRecord['conclusion'] ?? []) as $singleConclusion) {
                $conclusions[] = new Conclusion(
                    $singleConclusion['probability'],
                    Code::fromJson($singleConclusion['code']),
                    Explainability::fromJson($singleConclusion['explainability'])
                );
            }
            $imagingAnalysis[] = new ImagingAnalysisInstance(
                $conclusions,
                MediaValidity::fromJson($imagingAnalysisRecord['mediaValidity']),
                ImagingAnalysisInstancePerformanceIndicator::fromJson($imagingAnalysisRecord['performanceIndicator']),
                ClinicalIndicator::fromJson($imagingAnalysisRecord['clinicalIndicator'])
            );
        }

        return new self(
            $json['resourceType'],
            $json['status'],
            $clinicalIndicator,
            $performanceIndicator,
            $finalConclusions,
            $imagingAnalysis,
            $json['analysisDuration'],
            new DateTimeImmutable($json['issued'])
        );
    }

    /**
     * @return Conclusion[]
     */
    public function getPossibleConclusions(): array
    {
        return array_filter($this->conclusions, fn($conclusion) => $conclusion->isPossible);
    }

    /**
     * @return FailedMedia[]
     */
    public function getIndexOfFailedMedias(): array
    {
        $indexes = [];
        foreach ($this->imagingAnalysis as $index => $imagingAnalysisInstance) {
            if (!$imagingAnalysisInstance->mediaValidity->isValid) {
                $indexes[] = new FailedMedia(
                    $index,
                    $imagingAnalysisInstance->mediaValidity
                );
            }
        }
        return $indexes;
    }
}
