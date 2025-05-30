<?php

namespace LegitHealth\MedicalDevice\Common;

use JsonSerializable;

final readonly class Code implements JsonSerializable
{
    /**
     * @param CodingItem[] $coding
     */
    public function __construct(
        public ?array $coding,
        public string $text
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            isset($json['coding']) ? array_map(fn($json) => CodingItem::fromJson($json), $json['coding']) : null,
            $json['text'],
        );
    }

    public function jsonSerialize(): mixed
    {
        return [
            'text' => $this->text,
            'coding' => $this->coding === null ? null : array_map(fn($codingItem) => $codingItem->asArray(), $this->coding)
        ];
    }
}
