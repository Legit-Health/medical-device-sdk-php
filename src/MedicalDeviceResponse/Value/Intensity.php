<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

enum Intensity: int
{
    case None = 0;
    case Low = 1;
    case Moderate = 2;
    case High = 3;
}
