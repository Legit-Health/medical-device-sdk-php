<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

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

    public function toArray(): array
    {
        return [
            'item' => [
                'pruritus' => $this->pruritus
            ]
        ];
    }
}
