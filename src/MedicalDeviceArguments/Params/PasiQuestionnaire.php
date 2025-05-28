<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class PasiQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $surface,
        public int $erythema,
        public int $induration,
        public int $desquamation
    ) {
        $this->ensureIsInRange($surface, 0, 6, 'surface');
        $this->ensureIsInRange($erythema, 0, 4, 'erythema');
        $this->ensureIsInRange($induration, 0, 4, 'induration');
        $this->ensureIsInRange($desquamation, 0, 4, 'desquamation');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Pasi->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'questionnaireResponse' => [
                'item' => [
                    'surface' => $this->surface,
                    'erythema' => $this->erythema,
                    'induration' => $this->induration,
                    'desquamation' => $this->desquamation,
                ]
            ]
        ];
    }
}
