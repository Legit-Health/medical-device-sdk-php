<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

readonly class UasQuestionnaire extends Questionnaire
{
    public function __construct(public int $pruritus, public int $wheals)
    {
        $this->ensureIsInRange($pruritus, 0, 3, 'pruritus');
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
                    'pruritus' => $this->pruritus,
                    'wheals' => $this->wheals,
                ]
            ]
        ];
    }
}
