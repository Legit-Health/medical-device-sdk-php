<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class Ihs4LocalQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $nodule,
        public int $abscess,
        public int $drainingTunnel
    ) {
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Ihs4Local->value;
    }

    public function toArray(): array
    {
        return [
            'questionnaire' => $this::getName(),
            'item' => [
                [
                    'code' => 'nodule',
                    'answer' => [
                        [
                            'value' => $this->nodule
                        ]
                    ]
                ],
                [
                    'code' => 'abscess',
                    'answer' => [
                        [
                            'value' => $this->abscess
                        ]
                    ]
                ],
                [
                    'code' => 'drainingTunnel',
                    'answer' => [
                        [
                            'value' => $this->drainingTunnel
                        ]
                    ]
                ]
            ]
        ];
    }
}
