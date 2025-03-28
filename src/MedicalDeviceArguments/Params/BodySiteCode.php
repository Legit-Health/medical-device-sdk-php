<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

enum BodySiteCode: string
{
    case HeadFront = 'headFront';
    case HeadBack = 'headBack';
    case HeadTop = 'headTop';
    case HeadLeft = 'headLeft';
    case HeadRight = 'headRight';
    case ArmLeft = 'armLeft';
    case ArmRight = 'armRight';
    case TrunkFront = 'trunkFront';
    case TrunkBack = 'trunkBack';
    case LegLeft = 'legLeft';
    case LegRight = 'legRight';
    case HandLeft = 'handLeft';
    case HandRight = 'handRight';
    case FootLeft = 'footLeft';
    case FootRight = 'footRight';
    case Genital = 'genital';
    case Nails = 'nails';
    case Scalp = 'scalp';
    case EarLeft = 'earLeft';
    case EarRight = 'earRight';
    case Perioral = 'perioral';
    case Tongue = 'tongue';
    case ElbowLeft = 'elbowLeft';
    case ElbowRight = 'elbowRight';
    case UpperArmLeft = 'upperArmLeft';
    case UpperArmRight = 'upperArmRight';
    case LowerArmLeft = 'lowerArmLeft';
    case LowerArmRight = 'lowerArmRight';
    case KneeLeft = 'kneeLeft';
    case KneeRight = 'kneeRight';
    case UpperLegLeft = 'upperLegLeft';
    case UpperLegRight = 'upperLegRight';
    case LowerLegLeft = 'lowerLegLeft';
    case LowerLegRight = 'lowerLegRight';
}
