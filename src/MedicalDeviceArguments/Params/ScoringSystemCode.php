<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

enum ScoringSystemCode: string
{
    case Aihs4Local = 'aihs4Local';
    case AladinLocal = 'aladinLocal';
    case ApasiLocal = 'apasiLocal';
    case ApulsiLocal = 'apulsiLocal';
    case AscoradLocal = 'ascoradLocal';
    case AuasLocal = 'auasLocal';
    case Dlqi = 'dlqi';
    case Gags = 'gags';
    case Ihs4Local = 'ihs4Local';
    case Pga = 'pga';
    case Pure4 = 'pure4';
    case PasiLocal = 'pasiLocal';
    case ResvechLocal = 'resvechLocal';
    case SevenPc = '7Pc';
    case ScoradLocal = 'scoradLocal';
    case Scovid = 'scovid';
    case UasLocal = 'uasLocal';
    case Uct = 'uct';
}
