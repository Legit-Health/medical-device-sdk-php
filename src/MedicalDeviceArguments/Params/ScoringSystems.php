<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly final class ScoringSystems
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

    public function asArray(): array
    {
        $json = [];
        foreach ($this->questionnaires as $questionnaire) {
            $json[$questionnaire->getName()] = $questionnaire;
        }
        return $json;
    }
}
