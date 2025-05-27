<?php

namespace LegitHealth\MedicalDevice\Common;

final readonly class CodingItem
{
    public function __construct(
        public string $code,
        public ?string $display,
        public ?string $system,
        public string $systemDisplay,
        public ?string $version
    ) {}

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
}
