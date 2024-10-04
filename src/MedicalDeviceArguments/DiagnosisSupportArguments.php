<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\Subject;

readonly class DiagnosisSupportArguments implements MedicalDeviceArguments
{
    /**
     * @param string[] $medias
     */
    public function __construct(
        public array $medias,
        public ?Subject $subject = null
    ) {
    }

    public function toArray(): array
    {
        $json = [
            "media" => array_map(fn (string $mediaContent) => [
                'data' => $mediaContent
            ], $this->medias)
        ];
        $subject = $this->subject;
        if ($subject !== null) {
            $json["subject"] = $subject->toArray();
        }
        return $json;
    }
}
