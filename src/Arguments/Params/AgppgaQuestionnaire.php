<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

use stdClass;

readonly class AgppgaQuestionnaire extends Questionnaire
{
    public function __construct() {}

    public static function getName(): string
    {
        return ScoringSystemCode::Agppga->value;
    }

    public function jsonSerialize(): mixed
    {
        return new stdClass();
    }
}
