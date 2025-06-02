<?php

namespace LegitHealth\MedicalDevice\Arguments\Params;

enum Operator: string
{
    case Patient = 'Patient';
    case Practitioner = 'Practitioner';
}
