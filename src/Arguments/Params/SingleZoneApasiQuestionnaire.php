<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

readonly class SingleZoneApasiQuestionnaire extends Questionnaire
{
    public function __construct(public float $surface)
    {
        $this->ensureIsInRange($surface, 0, 6, 'surface');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Apasi->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'questionnaireResponse' => [
                'item' => [
                    'surface' => \intval($this->surface)
                ]
            ]
        ];
    }
}
