<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

use JsonSerializable;

final readonly class ScoringSystems implements JsonSerializable
{
    /**
     * @param Questionnaire[] $questionnaires
     */
    public function __construct(
        public array $questionnaires
    ) {}

    public static function createEmpty(): self
    {
        return new self([]);
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        foreach ($this->questionnaires as $questionnaire) {
            $json[$questionnaire::getName()] = $questionnaire;
        }
        return $json;
    }
}
