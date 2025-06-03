<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

use stdClass;

readonly class SingleZoneAladinQuestionnaire extends Questionnaire
{
    public function __construct() {}

    public static function getName(): string
    {
        return ScoringSystemCode::Aladin->value;
    }

    public function jsonSerialize(): mixed
    {
        return new stdClass();
    }
}
