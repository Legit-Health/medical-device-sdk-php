<?php

namespace LegitHealth\MedicalDevice\Arguments;

use InvalidArgumentException;

readonly class ImageValidationArguments implements MedicalDeviceArguments
{
    /**
     * @param string[] $medias
     */
    public function __construct(
        public array $medias
    ) {
        if (\count($medias) === 0 || \count($medias) > 5) {
            throw new InvalidArgumentException('The medias array must contain between 1 and 5 images');
        }
    }

    public function jsonSerialize(): mixed
    {
        return [
            "payload" => array_map(fn($attachment) => [
                "contentAttachment" => ["data" => $attachment]
            ], $this->medias)
        ];
    }
}
