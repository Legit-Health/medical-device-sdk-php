<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

use stdClass;

readonly class Aihs4Questionnaire extends Questionnaire
{
    public function __construct() {}

    public static function getName(): string
    {
        return ScoringSystemCode::Aihs4->value;
    }

    public function jsonSerialize(): mixed
    {
        return new stdClass;
    }
}
