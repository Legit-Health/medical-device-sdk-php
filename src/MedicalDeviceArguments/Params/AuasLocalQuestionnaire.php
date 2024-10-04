<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class AuasLocalQuestionnaire extends Questionnaire
{
    public function __construct(public int $itchiness)
    {
        $this->ensureIsInRange($itchiness, 0, 3, 'itchiness');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::AuasLocal->value;
    }

    public function toArray(): array
    {
        return [
            'questionnaire' => $this::getName(),
            'item' => [
                [
                    'code' => 'itchiness',
                    'answer' => [
                        [
                            'value' => $this->itchiness
                        ]
                    ]
                ]
            ]
        ];
    }
}
