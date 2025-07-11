<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

readonly class Ihs4Questionnaire extends Questionnaire
{
    public function __construct(
        public int $nodule,
        public int $abscess,
        public int $drainingTunnel
    ) {}

    public static function getName(): string
    {
        return ScoringSystemCode::Ihs4->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'questionnaireResponse' => [
                'item' => [
                    'nodule' => $this->nodule,
                    'abscess' => $this->abscess,
                    'drainingTunnel' => $this->drainingTunnel
                ]
            ]
        ];
    }
}
