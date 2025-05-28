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

    public function jsonSerialize(): mixed
    {
        return [
            'questionnaireResponse' => [
                'item' => [
                    'question1' => $this->question1,
                    'question2' => $this->question2,
                    'question3' => $this->question3,
                    'question4' => $this->question4,
                    'question5' => $this->question5,
                    'question6' => $this->question6,
                    'question7' => $this->question7,
                    'question8' => $this->question8,
                    'question9' => $this->question9,
                    'question10' => $this->question10
                ]
            ]
        ];
    }
}
