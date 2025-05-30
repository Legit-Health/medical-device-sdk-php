<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

use ArrayObject;

readonly class SingleZoneAgppgaQuestionnaire extends Questionnaire
{
    public function __construct()
    {
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Agppga->value;
    }

    public function jsonSerialize(): mixed
    {
        return new ArrayObject();
    }
}
