<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class ScovidQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $pain,
        public int $itchiness,
        public int $fever,
        public int $cough,
        public int $cephalea,
        public int $myalgiaOrArthralgia,
        public int $malaise,
        public int $lossOfTasteOrOlfactory,
        public int $shortnessOfBreath,
        public int $otherSkinProblems,
    ) {
        $this->ensureIsInRange($pain, 0, 10, 'pain');
        $this->ensureIsInRange($itchiness, 0, 10, 'itchiness');
        $this->ensureIsInRange($fever, 0, 3, 'fever');
        $this->ensureIsInRange($cough, 0, 3, 'cough');
        $this->ensureIsInRange($cephalea, 0, 3, 'cephalea');
        $this->ensureIsInRange($myalgiaOrArthralgia, 0, 3, 'myalgiaOrArthralgia');
        $this->ensureIsInRange($malaise, 0, 3, 'malaise');
        $this->ensureIsInRange($lossOfTasteOrOlfactory, 0, 3, 'lossOfTasteOrOlfactory');
        $this->ensureIsInRange($shortnessOfBreath, 0, 3, 'shortnessOfBreath');
        $this->ensureIsInRange($otherSkinProblems, 0, 3, 'otherSkinProblems');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Scovid->value;
    }

    public function toArray(): array
    {
        return [
            'questionnaire' => $this::getName(),
            'item' => [
                [
                    'code' => 'pain',
                    'answer' => [
                        [
                            'value' => $this->pain
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
                    'code' => 'fever',
                    'answer' => [
                        [
                            'value' => $this->fever
                        ]
                    ]
                ],
                [
                    'code' => 'cough',
                    'answer' => [
                        [
                            'value' => $this->cough
                        ]
                    ]
                ],
                [
                    'code' => 'cephalea',
                    'answer' => [
                        [
                            'value' => $this->cephalea
                        ]
                    ]
                ],
                [
                    'code' => 'myalgiaOrArthralgia',
                    'answer' => [
                        [
                            'value' => $this->myalgiaOrArthralgia
                        ]
                    ]
                ],
                [
                    'code' => 'malaise',
                    'answer' => [
                        [
                            'value' => $this->malaise
                        ]
                    ]
                ],
                [
                    'code' => 'lossOfTasteOrOlfactory',
                    'answer' => [
                        [
                            'value' => $this->lossOfTasteOrOlfactory
                        ]
                    ]
                ],
                [
                    'code' => 'shortnessOfBreath',
                    'answer' => [
                        [
                            'value' => $this->shortnessOfBreath
                        ]
                    ]
                ],
                [
                    'code' => 'otherSkinProblems',
                    'answer' => [
                        [
                            'value' => $this->otherSkinProblems
                        ]
                    ]
                ]
            ]
        ];
    }
}
