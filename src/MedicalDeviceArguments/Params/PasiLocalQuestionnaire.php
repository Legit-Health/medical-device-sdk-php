<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class PasiLocalQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $surface,
        public int $erythema,
        public int $induration,
        public int $desquamation
    ) {
        $this->ensureIsInRange($surface, 0, 6, 'surface');
        $this->ensureIsInRange($erythema, 0, 4, 'erythema');
        $this->ensureIsInRange($induration, 0, 4, 'induration');
        $this->ensureIsInRange($desquamation, 0, 4, 'desquamation');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::PasiLocal->value;
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
                    'code' => 'erythema',
                    'answer' => [
                        [
                            'value' => $this->erythema
                        ]
                    ]
                ],
                [
                    'code' => 'induration',
                    'answer' => [
                        [
                            'value' => $this->induration
                        ]
                    ]
                ],
                [
                    'code' => 'desquamation',
                    'answer' => [
                        [
                            'value' => $this->desquamation
                        ]
                    ]
                ]
            ]
        ];
    }
}
