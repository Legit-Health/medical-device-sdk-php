<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\ScoringSystemCode;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\{AdditionalData, Attachment, Box, Detection, DetectionCode, FacetScore, MediaValidity, Point2d, ScoringSystemResult, ScoringSystemScore, ValidityMetric};

readonly class SeverityAssessmentResponse
{
    /**
     * @param array<string,ScoringSystemResult> $scoringSystemsResults
     */
    public function __construct(
        public string $modality,
        public MediaValidity $mediaValidity,
        public array $scoringSystemsResults,
        public float $analysisDuration
    ) {
    }

    public static function fromJson(array $json): self
    {
        $scoringSystemsResults = [];
        foreach ($json['patientEvolution'] as $scoringSystemCode => $patientEvolutionRecord) {
            $facetScores = [];
            foreach ($patientEvolutionRecord['items'] as $item) {
                /** @var string */
                $facetCode = $item['coding']['code'];

                $additionalData = [];
                if (isset($item['additionalData'])) {
                    foreach ($item['additionalData'] as $additionalDataItem) {
                        $additionalData[] = new AdditionalData(
                            $additionalDataItem['coding']['code'],
                            $additionalDataItem['value']
                        );
                    }
                }

                $facetScores[$facetCode] = new FacetScore(
                    $facetCode,
                    $item['value'],
                    $additionalData
                );
            }

            $attachments = array_map(
                fn (array $attachment) => new Attachment(
                    $attachment['title'],
                    $attachment['contentType'],
                    $attachment['data'],
                    $attachment['height'],
                    $attachment['width']
                ),
                $patientEvolutionRecord['media']['attachments'] ?? []
            );

            $detections = array_map(
                fn (array $detection) => new Detection(
                    $detection['confidence'],
                    DetectionCode::from($detection['coding']['code']),
                    new Box(
                        new Point2d($detection['box']['x1'], $detection['box']['y1']),
                        new Point2d($detection['box']['x2'], $detection['box']['y2']),
                    )
                ),
                $patientEvolutionRecord['media']['detections'] ?? []
            );

            /** @var string $scoringSystemCode */
            $scoringSystemsResults[$scoringSystemCode] = new ScoringSystemResult(
                ScoringSystemCode::from($scoringSystemCode),
                new ScoringSystemScore(
                    interpretation: $patientEvolutionRecord['score']['interpretation'] ?? null,
                    value: $patientEvolutionRecord['score']['value']
                ),
                $facetScores,
                $attachments,
                $detections
            );
        }
        return new self(
            $json['media']['modality'],
            MediaValidity::fromJson($json['media']['validity']),
            $scoringSystemsResults,
            \floatval(str_replace(' secs', '', $json['analysisDuration'] ?? ''))
        );
    }

    public function getFailedValidityMetric(): ?ValidityMetric
    {
        return $this->mediaValidity->getFailedValidityMetric();
    }

    public function getScoringSystemResult(string|ScoringSystemCode $scoringSystemCode): ?ScoringSystemResult
    {
        return $this->scoringSystemsResults[is_string($scoringSystemCode) ? $scoringSystemCode : $scoringSystemCode->value] ?? null;
    }
}
