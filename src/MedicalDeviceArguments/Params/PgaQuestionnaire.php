<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class PgaQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $erythema,
        public int $desquamation,
        public int $induration
    ) {
        $this->ensureIsInRange($erythema, 0, 4, 'erythema');
        $this->ensureIsInRange($desquamation, 0, 4, 'desquamation');
        $this->ensureIsInRange($induration, 0, 4, 'induration');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Pga->value;
    }

    public function toArray(): array
    {
        return [
            'questionnaire' => $this::getName(),
            'item' => [
                [
                    'code' => 'erythema',
                    'answer' => [
                        [
                            'value' => $this->erythema
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
                ],
                [
                    'code' => 'induration',
                    'answer' => [
                        [
                            'value' => $this->induration
                        ]
                    ]
                ]
            ]
        ];
    }
}
