<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

use stdClass;

readonly class AvasiQuestionnaire extends Questionnaire
{
    public function __construct()
    {
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Avasi->value;
    }

    public function jsonSerialize(): mixed
    {
        return new stdClass();
    }
}
