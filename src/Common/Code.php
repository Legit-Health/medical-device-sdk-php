<?php

namespace LegitHealth\MedicalDevice\Common;

final readonly class Code
{
    /**
     * @param CodingItem[] $coding
     */
    public function __construct(
        public array $coding,
        public string $text
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            array_map(fn($json) => CodingItem::fromJson($json), $json['coding'] ?? []),
            $json['text'],
        );
    }

    public function asArray(): array
    {
        return [
            'text' => $this->text,
            'coding' => array_map(fn($codingItem) => $codingItem->asArray(), $this->coding)
        ];
    }
}
