<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

enum ModalityValue: string
{
    case None = 'None';
    case Clinical = 'Clinical';
    case Dermoscopic = 'Dermoscopic';
}
