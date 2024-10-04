<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class ApasiLocalQuestionnaire extends Questionnaire
{
    public function __construct(public int $surface)
    {
        $this->ensureIsInRange($surface, 0, 6, 'surface');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::ApasiLocal->value;
    }

    public function toArray(): array
    {
        return [
            'questionnaire' => $this::getName(),
            'item' => [
                [
                    'code' => 'surface',
                    'answer' => [
                        [
                            'value' => $this->surface
                        ]
                    ]
                ]
            ]
        ];
    }
}
