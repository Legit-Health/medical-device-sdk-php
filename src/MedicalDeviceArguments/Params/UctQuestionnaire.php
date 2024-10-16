<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class UctQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $physicalSymptoms,
        public int $qualityOfLife,
        public int $treatmentNotEnough,
        public int $overallUnderControl,
    ) {
        $this->ensureIsInRange($physicalSymptoms, 0, 4, 'physicalSymptoms');
        $this->ensureIsInRange($qualityOfLife, 0, 4, 'qualityOfLife');
        $this->ensureIsInRange($treatmentNotEnough, 0, 4, 'treatmentNotEnough');
        $this->ensureIsInRange($overallUnderControl, 0, 4, 'overallUnderControl');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Uct->value;
    }

    public function toArray(): array
    {
        return [
            'questionnaire' => $this::getName(),
            'item' => [
                [
                    'code' => 'physicalSymptoms',
                    'answer' => [
                        [
                            'value' => $this->physicalSymptoms
                        ]
                    ]
                ],
                [
                    'code' => 'qualityOfLife',
                    'answer' => [
                        [
                            'value' => $this->qualityOfLife
                        ]
                    ]
                ],
                [
                    'code' => 'treatmentNotEnough',
                    'answer' => [
                        [
                            'value' => $this->treatmentNotEnough
                        ]
                    ]
                ],
                [
                    'code' => 'overallUnderControl',
                    'answer' => [
                        [
                            'value' => $this->overallUnderControl
                        ]
                    ]
                ]
            ]
        ];
    }
}
