<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

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

    public function jsonSerialize(): mixed
    {
        return [
            'questionnaireResponse' => [
                'item' => [
                    'question1' => $this->question1,
                    'question2' => $this->question2,
                    'question3' => $this->question3,
                    'question4' => $this->question4,
                ]
            ]
        ];
    }
}
