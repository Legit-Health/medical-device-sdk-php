<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class SingleZoneApasiQuestionnaire extends Questionnaire
{
    public function __construct(public float $surface)
    {
        $this->ensureIsInRange($surface, 0, 100, 'surface');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Apasi->value;
    }

    public function toArray(): array
    {
        return [
            'item' => [
                'surface' => $this->surface
            ]
        ];
    }
}
