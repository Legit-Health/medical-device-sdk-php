<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\ScoringSystemCode;

final readonly class PatientEvolutionInstance
{
    /**
     * @param array<string,EvolutionItem> $items
     * @param Attachment[] $attachments
     * @param Detection[] $detections
     * */
    public function __construct(
        public ScoringSystemCode $scoringSystemCode,
        public ScoringSystemScore $score,
        public array $items,
        public array $attachments,
        public array $detections
    ) {
    }

    public function getEvolutionItem(string $code): EvolutionItem
    {
        return $this->items[$code];
    }

    /**
     * @return EvolutionItem[]
     */
    public function getEvolutionItems(): array
    {
        return array_values($this->items);
    }
}
