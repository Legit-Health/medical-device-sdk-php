<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class KnownCondition
{
    public function __construct(
        public string $display,
        public ?string $icdCode = null
    ) {
    }

    public function toArray(): array
    {
        $json = [
            'display' => $this->display
        ];
        if ($this->icdCode !== null) {
            $json['code'] = $this->icdCode;
            $json['systemAlias'] = 'ICD-11';
        }
        return $json;
    }
}
