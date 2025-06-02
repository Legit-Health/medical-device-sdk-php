<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

use stdClass;

readonly class NsilQuestionnaire extends Questionnaire
{
    public function __construct() {}

    public static function getName(): string
    {
        return ScoringSystemCode::Nsil->value;
    }

    public function jsonSerialize(): mixed
    {
        return new stdClass();
    }
}
