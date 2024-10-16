<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\ScoringSystemCode;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\{
    AdditionalData,
    Attachment,
    Box,
    Detection,
    DetectionLabel,
    EvolutionItem,
    Media,
    MediaValidity,
    PatientEvolutionInstance,
    Point2d,
    ScoringSystemScore,
    ValidityMetric
};

readonly class SeverityAssessmentResponse
{
    /**
     * @param array<string,PatientEvolutionInstance> $patientEvolution
     */
    public function __construct(
        public Media $media,
        public array $patientEvolution,
        public float $analysisDuration
    ) {
    }

    public static function fromJson(array $json): self
    {
        $patientEvolution = [];
        /** @var string $scoringSystemCode */
        foreach ($json['patientEvolution'] as $scoringSystemCode => $patientEvolutionInstance) {
            $evolutionItems = [];
            foreach ($patientEvolutionInstance['items'] as $item) {
                /** @var string */
                $evolutionItemCode = $item['coding']['code'];

                $additionalData = [];
                if (isset($item['additionalData'])) {
                    foreach ($item['additionalData'] as $additionalDataItem) {
                        $additionalData[] = new AdditionalData(
                            $additionalDataItem['coding']['code'],
                            $additionalDataItem['value']
                        );
                    }
                }

                $evolutionItems[$evolutionItemCode] = new EvolutionItem(
                    $evolutionItemCode,
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
                $patientEvolutionInstance['media']['attachments'] ?? []
            );

            $detections = array_map(
                fn (array $detection) => new Detection(
                    $detection['confidence'],
                    DetectionLabel::from($detection['coding']['code']),
                    new Box(
                        new Point2d($detection['box']['x1'], $detection['box']['y1']),
                        new Point2d($detection['box']['x2'], $detection['box']['y2']),
                    )
                ),
                $patientEvolutionInstance['media']['detections'] ?? []
            );

            $patientEvolution[$scoringSystemCode] = new PatientEvolutionInstance(
                ScoringSystemCode::from($scoringSystemCode),
                new ScoringSystemScore(
                    interpretation: $patientEvolutionInstance['score']['interpretation'] ?? null,
                    value: $patientEvolutionInstance['score']['value']
                ),
                $evolutionItems,
                $attachments,
                $detections
            );
        }
        return new self(
            new Media(
                $json['media']['modality'],
                MediaValidity::fromJson($json['media']['validity']),
            ),
            $patientEvolution,
            \floatval(str_replace(' secs', '', $json['analysisDuration'] ?? ''))
        );
    }

    public function getFailedValidityMetric(): ?ValidityMetric
    {
        return $this->media->validity->getFailedValidityMetric();
    }

    public function getPatientEvolutionInstance(string|ScoringSystemCode $scoringSystemCode): ?PatientEvolutionInstance
    {
        return $this->patientEvolution[is_string($scoringSystemCode) ? $scoringSystemCode : $scoringSystemCode->value] ?? null;
    }
}
