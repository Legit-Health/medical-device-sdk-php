<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

readonly class SevenPcQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $changeInSize,
        public int $irregularPigmentation,
        public int $irregularBorder,
        public int $inflammation,
        public int $largerThanOtherLesions,
        public int $itchOrAlteredSensation,
        public int $crustingOrBleeding
    ) {
        $this->ensureIsInRange($changeInSize, 0, 1, 'changeInSize');
        $this->ensureIsInRange($irregularPigmentation, 0, 1, 'irregularPigmentation');
        $this->ensureIsInRange($irregularBorder, 0, 1, 'irregularBorder');
        $this->ensureIsInRange($inflammation, 0, 1, 'inflammation');
        $this->ensureIsInRange($largerThanOtherLesions, 0, 1, 'largerThanOtherLesions');
        $this->ensureIsInRange($itchOrAlteredSensation, 0, 1, 'itchOrAlteredSensation');
        $this->ensureIsInRange($crustingOrBleeding, 0, 1, 'crustingOrBleeding');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::SevenPc->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'questionnaireResponse' => [
                'item' => [
                    'changeInSize' => $this->changeInSize,
                    'irregularPigmentation' => $this->irregularPigmentation,
                    'irregularBorder' => $this->irregularBorder,
                    'inflammation' => $this->inflammation,
                    'largerThanOtherLesions' => $this->largerThanOtherLesions,
                    'itchOrAlteredSensation' => $this->itchOrAlteredSensation,
                    'crustingOrBleeding' => $this->crustingOrBleeding,
                ]
            ]
        ];
    }
}
