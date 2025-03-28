<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class SevenPcQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $irregularSize,
        public int $irregularPigmentation,
        public int $irregularBorder,
        public int $inflammation,
        public int $largerThanOtherLesions,
        public int $itchOrAltered,
        public int $crustedOrBleeding
    ) {
        $this->ensureIsInRange($irregularSize, 0, 1, 'irregularSize');
        $this->ensureIsInRange($irregularPigmentation, 0, 1, 'irregularPigmentation');
        $this->ensureIsInRange($irregularBorder, 0, 1, 'irregularBorder');
        $this->ensureIsInRange($inflammation, 0, 1, 'inflammation');
        $this->ensureIsInRange($largerThanOtherLesions, 0, 1, 'largerThanOtherLesions');
        $this->ensureIsInRange($itchOrAltered, 0, 1, 'itchOrAltered');
        $this->ensureIsInRange($crustedOrBleeding, 0, 1, 'crustedOrBleeding');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::SevenPc->value;
    }
    public function toArray(): array
    {
        return [
            'item' => [
                'irregularSize' => $this->irregularSize,
                'irregularPigmentation' => $this->irregularPigmentation,
                'irregularBorder' => $this->irregularBorder,
                'inflammation' => $this->inflammation,
                'largerThanOtherLesions' => $this->largerThanOtherLesions,
                'itchOrAltered' => $this->itchOrAltered,
                'crustedOrBleeding' => $this->crustedOrBleeding,
            ]
        ];
    }
}
