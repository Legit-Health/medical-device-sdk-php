<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class UasLocalQuestionnaire extends Questionnaire
{
    public function __construct(public int $itchiness, public int $hive)
    {
        $this->ensureIsInRange($itchiness, 0, 3, 'itchiness');
        $this->ensureIsInRange($hive, 0, 3, 'hive');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::UasLocal->value;
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
                ],
                [
                    'code' => 'hive',
                    'answer' => [
                        [
                            'value' => $this->hive
                        ]
                    ]
                ]
            ]
        ];
    }
}
