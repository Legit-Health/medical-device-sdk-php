<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

use stdClass;

readonly class AladinQuestionnaire extends Questionnaire
{
    public function __construct()
    {
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Aladin->value;
    }

    public function jsonSerialize(): mixed
    {
        return new stdClass();
    }
}
