<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\ScoringSystemCode;

final readonly class PatientEvolutionInstance
{
    /**
     * @param array<string,EvolutionItem>|null $item
     * */
    public function __construct(
        public ScoringSystemCode $scoringSystemCode,
        public ScoringSystemScore $score,
        public ?array $item,
        public ?PatientEvolutionInstanceMedia $media
    ) {}

    public function getEvolutionItem(string $code): ?EvolutionItem
    {
        if ($this->item === null || !isset($this->item[$code])) {
            return null;
        }
        return $this->item[$code];
    }

    /**
     * @return EvolutionItem[]
     */
    public function getEvolutionItems(): ?array
    {
        if ($this->item === null) {
            return null;
        }
        return array_values($this->item);
    }
}
