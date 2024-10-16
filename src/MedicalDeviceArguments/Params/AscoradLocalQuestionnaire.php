<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class AscoradLocalQuestionnaire extends Questionnaire
{
    public function __construct(
        public float $surface,
        public int $itchiness,
        public int $sleeplessness
    ) {
        $this->ensureIsInRange($surface, 0, 100, 'surface');
        $this->ensureIsInRange($itchiness, 0, 10, 'itchiness');
        $this->ensureIsInRange($sleeplessness, 0, 10, 'sleeplessness');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::AscoradLocal->value;
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
                ],
                [
                    'code' => 'itchiness',
                    'answer' => [
                        [
                            'value' => $this->itchiness
                        ]
                    ]
                ],
                [
                    'code' => 'sleeplessness',
                    'answer' => [
                        [
                            'value' => $this->sleeplessness
                        ]
                    ]
                ]
            ]
        ];
    }
}
