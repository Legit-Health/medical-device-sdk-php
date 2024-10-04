<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

enum Operator: string
{
    case Patient = 'Patient';
    case Practitioner = 'Practitioner';
}
