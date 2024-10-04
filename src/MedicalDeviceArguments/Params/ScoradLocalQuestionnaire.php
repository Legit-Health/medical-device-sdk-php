<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class ScoradLocalQuestionnaire extends Questionnaire
{
    public function __construct(
        public float $surface,
        public int $erythema,
        public int $swelling,
        public int $crusting,
        public int $excoriation,
        public int $lichenification,
        public int $dryness,
        public int $itchiness,
        public int $sleeplessness
    ) {
        $this->ensureIsInRange($surface, 0, 100, 'surface');
        $this->ensureIsInRange($erythema, 0, 3, 'erythema');
        $this->ensureIsInRange($swelling, 0, 3, 'swelling');
        $this->ensureIsInRange($crusting, 0, 3, 'crusting');
        $this->ensureIsInRange($excoriation, 0, 3, 'excoriation');
        $this->ensureIsInRange($lichenification, 0, 3, 'lichenification');
        $this->ensureIsInRange($dryness, 0, 3, 'dryness');
        $this->ensureIsInRange($itchiness, 0, 10, 'itchiness');
        $this->ensureIsInRange($sleeplessness, 0, 10, 'sleeplessness');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::ScoradLocal->value;
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
                    'code' => 'swelling',
                    'answer' => [
                        [
                            'value' => $this->swelling
                        ]
                    ]
                ],
                [
                    'code' => 'crusting',
                    'answer' => [
                        [
                            'value' => $this->crusting
                        ]
                    ]
                ],
                [
                    'code' => 'excoriation',
                    'answer' => [
                        [
                            'value' => $this->excoriation
                        ]
                    ]
                ],
                [
                    'code' => 'lichenification',
                    'answer' => [
                        [
                            'value' => $this->lichenification
                        ]
                    ]
                ],
                [
                    'code' => 'dryness',
                    'answer' => [
                        [
                            'value' => $this->dryness
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
                ],
            ]
        ];
    }
}
