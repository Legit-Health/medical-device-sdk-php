<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class UasQuestionnaire extends Questionnaire
{
    public function __construct(public int $itchiness, public int $hive)
    {
        $this->ensureIsInRange($itchiness, 0, 3, 'itchiness');
        $this->ensureIsInRange($hive, 0, 3, 'hive');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Uas->value;
    }

    public function toArray(): array
    {
        return [
            'item' => [
                'itchiness' => $this->itchiness,
                'hive' => $this->hive,
            ]
        ];
    }
}
