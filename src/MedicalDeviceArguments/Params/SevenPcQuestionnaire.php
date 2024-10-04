<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class SevenPcQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $irregularSize,
        public int $irregularPigmentation,
        public int $irregularBorder,
        public int $inflammation,
        public int $largerThanOtherLesions,
        public int $itchOrAltered,
        public int $crustedOrBleeding
    ) {
        $this->ensureIsInRange($irregularSize, 0, 1, 'irregularSize');
        $this->ensureIsInRange($irregularPigmentation, 0, 1, 'irregularPigmentation');
        $this->ensureIsInRange($irregularBorder, 0, 1, 'irregularBorder');
        $this->ensureIsInRange($inflammation, 0, 1, 'inflammation');
        $this->ensureIsInRange($largerThanOtherLesions, 0, 1, 'largerThanOtherLesions');
        $this->ensureIsInRange($itchOrAltered, 0, 1, 'itchOrAltered');
        $this->ensureIsInRange($crustedOrBleeding, 0, 1, 'crustedOrBleeding');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::SevenPc->value;
    }

    public function toArray(): array
    {
        return [
            'questionnaire' => $this::getName(),
            'item' => [
                [
                    'code' => 'irregularSize',
                    'answer' => [
                        [
                            'value' => $this->irregularSize
                        ]
                    ]
                ],
                [
                    'code' => 'irregularPigmentation',
                    'answer' => [
                        [
                            'value' => $this->irregularPigmentation
                        ]
                    ]
                ],
                [
                    'code' => 'irregularBorder',
                    'answer' => [
                        [
                            'value' => $this->irregularBorder
                        ]
                    ]
                ],
                [
                    'code' => 'inflammation',
                    'answer' => [
                        [
                            'value' => $this->inflammation
                        ]
                    ]
                ],
                [
                    'code' => 'largerThanOtherLesions',
                    'answer' => [
                        [
                            'value' => $this->largerThanOtherLesions
                        ]
                    ]
                ],
                [
                    'code' => 'itchOrAltered',
                    'answer' => [
                        [
                            'value' => $this->itchOrAltered
                        ]
                    ]
                ],
                [
                    'code' => 'crustedOrBleeding',
                    'answer' => [
                        [
                            'value' => $this->crustedOrBleeding
                        ]
                    ]
                ]
            ]
        ];
    }
}
