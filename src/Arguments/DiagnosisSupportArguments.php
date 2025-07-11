<?php

namespace LegitHealth\MedicalDevice\Arguments;

use InvalidArgumentException;

readonly class DiagnosisSupportArguments implements MedicalDeviceArguments
{
    /**
     * @param string[] $medias
     */
    public function __construct(
        public array $medias
    ) {
        if (\count($medias) > 3) {
            throw new InvalidArgumentException('The maximum lenght of the medias array is 3');
        }
    }

    public function jsonSerialize(): mixed
    {
        $json = [
            "payload" => array_map(fn($attachment) => [
                "contentAttachment" => ["data" => $attachment]
            ], $this->medias)

        ];
        return $json;
    }
}
