<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class ScoringSystems
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

    public function toArray(): array
    {
        $json = [];
        foreach ($this->questionnaires as $questionnaire) {
            $json[$questionnaire->getName()] = [
                "calculate" => true,
                "questionnaireResponse" => $questionnaire->toArray()
            ];
        }
        return $json;
    }
}
