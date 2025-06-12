<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

readonly class UctQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $manifestations,
        public int $qualityOfLife,
        public int $treatmentNotEnough,
        public int $overallUnderControl,
    ) {
        $this->ensureIsInRange($manifestations, 0, 4, 'manifestations');
        $this->ensureIsInRange($qualityOfLife, 0, 4, 'qualityOfLife');
        $this->ensureIsInRange($treatmentNotEnough, 0, 4, 'treatmentNotEnough');
        $this->ensureIsInRange($overallUnderControl, 0, 4, 'overallUnderControl');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Uct->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'questionnaireResponse' => [
                'item' => [
                    'manifestations' => $this->manifestations,
                    'qualityOfLife' => $this->qualityOfLife,
                    'treatmentNotEnough' => $this->treatmentNotEnough,
                    'overallUnderControl' => $this->overallUnderControl,
                ]
            ]
        ];
    }
}
