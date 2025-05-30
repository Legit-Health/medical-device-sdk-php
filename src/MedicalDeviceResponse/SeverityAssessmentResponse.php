<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse;

use DateTimeImmutable;
use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\ScoringSystemCode;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\{
    Attachment,
    Detection,
    EvolutionItem,
    MediaValidity,
    PatientEvolutionInstance,
    PatientEvolutionInstanceMedia,
    ScoringSystemScore,
};

readonly class SeverityAssessmentResponse
{
    /**
     * @param array<string,PatientEvolutionInstance> $patientEvolution
     */
    public function __construct(
        public string $resourceType,
        public string $status,
        public ?MediaValidity $mediaValidity,
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
            $evolutionItems = null;
            if ($patientEvolutionInstance['item'] !== null) {
                $evolutionItems = [];
                foreach ($patientEvolutionInstance['item'] as $itemCode => $itemJson) {
                    $evolutionItems[$itemCode] = EvolutionItem::fromJson($itemCode, $itemJson);
                }
            }

            $attachments = null;
            if (isset($patientEvolutionInstance['media']['attachment'])) {
                $attachments = [];
                foreach ($patientEvolutionInstance['media']['attachment'] as $attachmentCode => $attachmentJson) {
                    $attachments[$attachmentCode] = Attachment::fromJson($attachmentCode, $attachmentJson);
                }
            }

            $detections = array_map(
                fn(array $detectionJson) => Detection::fromJson($detectionJson),
                $patientEvolutionInstance['media']['detection'] ?? []
            );

            $patientEvolution[$scoringSystemCode] = new PatientEvolutionInstance(
                ScoringSystemCode::from($scoringSystemCode),
                ScoringSystemScore::fromJson($patientEvolutionInstance['score']),
                $evolutionItems,
                new PatientEvolutionInstanceMedia($attachments, $detections)
            );
        }
        return new self(
            $json['resourceType'],
            $json['status'],
            isset($json['mediaValidity']) ? MediaValidity::fromJson($json['mediaValidity']) : null,
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
