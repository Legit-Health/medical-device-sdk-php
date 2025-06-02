<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

use stdClass;

readonly class AsaltQuestionnaire extends Questionnaire
{
    public function __construct() {}

    public static function getName(): string
    {
        return ScoringSystemCode::Asalt->value;
    }

    public function jsonSerialize(): mixed
    {
        return new stdClass();
    }
}
