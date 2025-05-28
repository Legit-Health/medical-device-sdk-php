<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class SingleZoneAscoradQuestionnaire extends Questionnaire
{
    public function __construct(
        public float $surface,
        public int $pruritus,
        public int $sleeplessness
    ) {
        $this->ensureIsInRange($surface, 0, 100, 'surface');
        $this->ensureIsInRange($pruritus, 0, 10, 'pruritus');
        $this->ensureIsInRange($sleeplessness, 0, 10, 'sleeplessness');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Ascorad->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'questionnaireResponse' => [
                'item' => [
                    'surface' => $this->surface,
                    'pruritus' => $this->pruritus,
                    'sleeplessness' => $this->sleeplessness
                ]
            ]
        ];
    }
}
