<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class SingleZoneAeasiQuestionnaire extends Questionnaire
{
    public function __construct(public float $surface, public int $patientAge)
    {
        $this->ensureIsInRange($surface, 0, 100, 'surface');
        $this->ensureIsInRange($patientAge, 0, 150, 'patientAge');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Aeasi->value;
    }

    public function asArray(): array
    {
        return [
            'item' => [
                'surface' => $this->surface,
                'patientAge' => $this->patientAge
            ]
        ];
    }
}
