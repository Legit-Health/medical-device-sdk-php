<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class DlqiQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $question1,
        public int $question2,
        public int $question3,
        public int $question4,
        public int $question5,
        public int $question6,
        public int $question7,
        public int $question8,
        public int $question9,
        public int $question10
    ) {
        $this->ensureIsInRange($question1, 0, 3, 'question1');
        $this->ensureIsInRange($question2, 0, 3, 'question2');
        $this->ensureIsInRange($question3, 0, 3, 'question3');
        $this->ensureIsInRange($question4, 0, 3, 'question4');
        $this->ensureIsInRange($question5, 0, 3, 'question5');
        $this->ensureIsInRange($question6, 0, 3, 'question6');
        $this->ensureIsInRange($question7, 0, 3, 'question7');
        $this->ensureIsInRange($question8, 0, 3, 'question8');
        $this->ensureIsInRange($question9, 0, 3, 'question9');
        $this->ensureIsInRange($question10, 0, 3, 'question10');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Dlqi->value;
    }

    public function toArray(): array
    {
        return [
            'questionnaire' => $this::getName(),
            'item' => [
                [
                    'code' => 'question1',
                    'answer' => [
                        [
                            'value' => $this->question1
                        ]
                    ]
                ],
                [
                    'code' => 'question2',
                    'answer' => [
                        [
                            'value' => $this->question2
                        ]
                    ]
                ],
                [
                    'code' => 'question3',
                    'answer' => [
                        [
                            'value' => $this->question3
                        ]
                    ]
                ],
                [
                    'code' => 'question4',
                    'answer' => [
                        [
                            'value' => $this->question4
                        ]
                    ]
                ],
                [
                    'code' => 'question5',
                    'answer' => [
                        [
                            'value' => $this->question5
                        ]
                    ]
                ],
                [
                    'code' => 'question6',
                    'answer' => [
                        [
                            'value' => $this->question6
                        ]
                    ]
                ],
                [
                    'code' => 'question7',
                    'answer' => [
                        [
                            'value' => $this->question7
                        ]
                    ]
                ],
                [
                    'code' => 'question8',
                    'answer' => [
                        [
                            'value' => $this->question8
                        ]
                    ]
                ],
                [
                    'code' => 'question9',
                    'answer' => [
                        [
                            'value' => $this->question9
                        ]
                    ]
                ],
                [
                    'code' => 'question10',
                    'answer' => [
                        [
                            'value' => $this->question10
                        ]
                    ]
                ]
            ]
        ];
    }
}
