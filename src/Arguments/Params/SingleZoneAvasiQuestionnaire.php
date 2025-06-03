<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

use stdClass;

readonly class SingleZoneAvasiQuestionnaire extends Questionnaire
{
    public function __construct() {}

    public static function getName(): string
    {
        return ScoringSystemCode::Avasi->value;
    }

    public function jsonSerialize(): mixed
    {
        return new stdClass();
    }
}
