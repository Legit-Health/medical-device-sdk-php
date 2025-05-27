<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

use InvalidArgumentException;

abstract readonly class Questionnaire
{
    abstract public function asArray(): array;
    abstract public static function getName(): string;

    protected function ensureIsInRange(int|float $value, int $min, int $max, string $name): void
    {
        if ($value >= $min && $value <= $max) {
            return;
        }
        throw new InvalidArgumentException(sprintf(
            '%s should be between %d and %d',
            $name,
            $min,
            $max
        ));
    }
}
