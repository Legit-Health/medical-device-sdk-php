<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

readonly class ScoradQuestionnaire extends Questionnaire
{
    public function __construct(
        public float $surface,
        public int $erythema,
        public int $swelling,
        public int $crusting,
        public int $excoriation,
        public int $lichenification,
        public int $dryness,
        public int $pruritus,
        public int $sleeplessness
    ) {
        $this->ensureIsInRange($surface, 0, 100, 'surface');
        $this->ensureIsInRange($erythema, 0, 3, 'erythema');
        $this->ensureIsInRange($swelling, 0, 3, 'swelling');
        $this->ensureIsInRange($crusting, 0, 3, 'crusting');
        $this->ensureIsInRange($excoriation, 0, 3, 'excoriation');
        $this->ensureIsInRange($lichenification, 0, 3, 'lichenification');
        $this->ensureIsInRange($dryness, 0, 3, 'dryness');
        $this->ensureIsInRange($pruritus, 0, 10, 'pruritus');
        $this->ensureIsInRange($sleeplessness, 0, 10, 'sleeplessness');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::Scorad->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'questionnaireResponse' => [
                'item' => [
                    'surface' => $this->surface,
                    'erythema' => $this->erythema,
                    'swelling' => $this->swelling,
                    'crusting' => $this->crusting,
                    'excoriation' => $this->excoriation,
                    'lichenification' => $this->lichenification,
                    'dryness' => $this->dryness,
                    'pruritus' => $this->pruritus,
                    'sleeplessness' => $this->sleeplessness,
                ]
            ]
        ];
    }
}
