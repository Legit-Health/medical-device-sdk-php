<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\ScoringSystemCode;

final readonly class PatientEvolutionInstance
{
    /**
     * @param array<string,EvolutionItem>|null $item
     * @param array<string,Attachment> $attachment
     * @param Detection[] $detections
     * */
    public function __construct(
        public ScoringSystemCode $scoringSystemCode,
        public ScoringSystemScore $score,
        public ?array $item,
        public ?array $attachment,
        public array $detections
    ) {}

    public function getEvolutionItem(string $code): ?EvolutionItem
    {
        if ($this->item || !isset($this->item['code'])) {
            return null;
        }
        return $this->item[$code];
    }

    /**
     * @return EvolutionItem[]
     */
    public function getEvolutionItems(): array
    {
        return array_values($this->item);
    }
}
