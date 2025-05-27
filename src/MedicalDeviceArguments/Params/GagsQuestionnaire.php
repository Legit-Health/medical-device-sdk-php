<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class GagsQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $forehead,
        public int $rightCheek,
        public int $leftCheek,
        public int $nose,
        public int $chin,
        public int $chestAndUpperBack
    ) {
        $this->ensureIsInRange($forehead, 0, 4, 'forehead');
        $this->ensureIsInRange($rightCheek, 0, 4, 'rightCheek');
        $this->ensureIsInRange($leftCheek, 0, 4, 'leftCheek');
        $this->ensureIsInRange($nose, 0, 4, 'nose');
        $this->ensureIsInRange($chin, 0, 4, 'chin');
        $this->ensureIsInRange($chestAndUpperBack, 0, 4, 'chestAndUpperBack');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Gags->value;
    }

    public function asArray(): array
    {
        return [
            'item' => [
                'forehead' => $this->forehead,
                'rightCheek' => $this->rightCheek,
                'leftCheek' => $this->leftCheek,
                'nose' => $this->nose,
                'chin' => $this->chin,
                'chestAndUpperBack' => $this->chestAndUpperBack
            ]
        ];
    }
}
