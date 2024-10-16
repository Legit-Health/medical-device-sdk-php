<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\Subject;
use InvalidArgumentException;

readonly class DiagnosisSupportArguments implements MedicalDeviceArguments
{
    /**
     * @param string[] $medias
     */
    public function __construct(
        public array $medias,
        public ?Subject $subject = null
    ) {
        if (\count($medias) > 3) {
            throw new InvalidArgumentException('The maximum lenght of the medias array is 3');
        }
    }

    public function toArray(): array
    {
        $json = [
            "media" => array_map(fn ($mediaContent) => [
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
