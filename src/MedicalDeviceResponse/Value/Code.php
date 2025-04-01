<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Code
{
    /**
     * @param CodingItem[] $coding
     * @param string $text
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
}
