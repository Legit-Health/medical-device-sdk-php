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

    public function toArray(): array
    {
        return [
            'questionnaire' => $this::getName(),
            'item' => [
                ['code' => 'forehead', 'answer' => [['value' => $this->forehead]]],
                ['code' => 'rightCheek', 'answer' => [['value' => $this->rightCheek]]],
                ['code' => 'leftCheek', 'answer' => [['value' => $this->leftCheek]]],
                ['code' => 'nose', 'answer' => [['value' => $this->nose]]],
                ['code' => 'chin', 'answer' => [['value' => $this->chin]]],
                ['code' => 'chestAndUpperBack', 'answer' => [['value' => $this->chestAndUpperBack]]],
            ]
        ];
    }
}
