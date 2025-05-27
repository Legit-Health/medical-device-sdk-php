<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class SingleZoneAgppgaQuestionnaire extends Questionnaire
{
    public function __construct() {}

    public static function getName(): string
    {
        return ScoringSystemCode::Agppga->value;
    }

    public function asArray(): array
    {
        return [];
    }
}
