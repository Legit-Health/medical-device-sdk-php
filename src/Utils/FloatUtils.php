<?php

namespace LegitHealth\MedicalDevice\Utils;

class FloatUtils
{
    public static function floatOrNull(mixed $value): ?float
    {
        return \is_numeric($value) ? \floatval($value) : null;
    }
}
