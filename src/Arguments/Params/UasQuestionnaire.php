<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

readonly class UasQuestionnaire extends Questionnaire
{
    public function __construct(public int $itchiness, public int $wheals)
    {
        $this->ensureIsInRange($itchiness, 0, 3, 'itchiness');
        $this->ensureIsInRange($wheals, 0, 3, 'wheals');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Uas->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'questionnaireResponse' => [
                'item' => [
                    'itchiness' => $this->itchiness,
                    'wheals' => $this->wheals,
                ]
            ]
        ];
    }
}
