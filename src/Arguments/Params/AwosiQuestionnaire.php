<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

use stdClass;

readonly class AwosiQuestionnaire extends Questionnaire
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
