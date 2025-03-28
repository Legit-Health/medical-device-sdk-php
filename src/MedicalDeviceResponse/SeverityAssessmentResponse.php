<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse;

use DateTimeImmutable;
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
        public MediaValidity $mediaValidity,
        public array $patientEvolution,
        public float $analysisDuration,
        public DateTimeImmutable $issued
    ) {}

    public static function fromJson(array $json): self
    {
        $patientEvolution = [];
        /** @var string $scoringSystemCode */
        foreach ($json['patientEvolution'] as $scoringSystemCode => $patientEvolutionInstance) {
            if ($patientEvolutionInstance === null) {
                continue;
            }
            $evolutionItems = [];
            foreach ($patientEvolutionInstance['item'] as $itemCode => $itemJson) {
                $evolutionItems[$itemCode] = EvolutionItem::fromJson($itemCode, $itemJson);
            }

            $attachments = [];
            foreach ($patientEvolutionInstance['media']['attachment'] as $attachmentCode => $attachmentJson) {
                $attachments[$attachmentCode] = Attachment::fromJson($attachmentCode, $attachmentJson);
            }

            $detections = array_map(
                fn(array $detectionJson) => Detection::fromJson($detectionJson),
                $patientEvolutionInstance['media']['detection'] ?? []
            );

            $patientEvolution[$scoringSystemCode] = new PatientEvolutionInstance(
                ScoringSystemCode::from($scoringSystemCode),
                ScoringSystemScore::fromJson($patientEvolutionInstance['score']),
                $evolutionItems,
                $attachments,
                $detections
            );
        }
        return new self(
            MediaValidity::fromJson($json['mediaValidity']),
            $patientEvolution,
            $json['analysisDuration'],
            new DateTimeImmutable($json['issued'])
        );
    }

    public function getPatientEvolutionInstance(string|ScoringSystemCode $scoringSystemCode): ?PatientEvolutionInstance
    {
        return $this->patientEvolution[is_string($scoringSystemCode) ? $scoringSystemCode : $scoringSystemCode->value] ?? null;
    }
}
