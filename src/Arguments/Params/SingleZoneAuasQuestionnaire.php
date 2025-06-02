<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

readonly class SingleZoneAuasQuestionnaire extends Questionnaire
{
    public function __construct(public int $pruritus)
    {
        $this->ensureIsInRange($pruritus, 0, 3, 'pruritus');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Auas->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'questionnaireResponse' => [
                'item' => [
                    'pruritus' => $this->pruritus
                ]
            ]
        ];
    }
}
