<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

enum DetectionCode: string
{
    case AcneLesion = 'acneLesion';
    case Hive = 'hive';
    case DrainingTunnel = 'drainingTunnel';
    case Nodule = 'nodule';
    case Abscess = 'abscess';
}
