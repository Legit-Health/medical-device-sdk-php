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
            $questionnaireArray = $questionnaire->toArray();
            $json[$questionnaire->getName()] = [
                "calculate" => true
            ];
            if (\count($questionnaireArray) > 0) {
                $json[$questionnaire->getName()]['questionnaireResponse'] = $questionnaireArray;
            }
        }
        return $json;
    }
}
