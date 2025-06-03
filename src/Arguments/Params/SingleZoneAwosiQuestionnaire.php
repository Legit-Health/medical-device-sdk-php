<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

use stdClass;

readonly class SingleZoneAwosiQuestionnaire extends Questionnaire
{
    public function __construct() {}

    public static function getName(): string
    {
        return ScoringSystemCode::Awosi->value;
    }

    public function jsonSerialize(): mixed
    {
        return new stdClass();
    }
}
