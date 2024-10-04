<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class Pure4Questionnaire extends Questionnaire
{
    public function __construct(
        public int $question1,
        public int $question2,
        public int $question3,
        public int $question4
    ) {
        $this->ensureIsInRange($question1, 0, 1, 'question1');
        $this->ensureIsInRange($question2, 0, 1, 'question2');
        $this->ensureIsInRange($question3, 0, 1, 'question3');
        $this->ensureIsInRange($question4, 0, 1, 'question4');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Pure4->value;
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
                ]
            ]
        ];
    }
}
