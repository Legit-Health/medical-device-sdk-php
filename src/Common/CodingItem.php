<?php

namespace LegitHealth\MedicalDevice\Common;

use JsonSerializable;

final readonly class CodingItem implements JsonSerializable
{
    public function __construct(
        public string $code,
        public ?string $display,
        public ?string $system,
        public string $systemDisplay,
        public ?string $version
    ) {
    }

    public static function fromJson(array $json): self
    {
        return new self(
            $json['code'],
            $json['display'],
            $json['system'],
            $json['systemDisplay'],
            $json['version']
        );
    }

    public function asArray(): array
    {
        return [
            'code' => $this->code,
            'display' => $this->display,
            'system' => $this->system,
            'systemDisplay' => $this->systemDisplay,
            'version' => $this->version,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->asArray();
    }
}
