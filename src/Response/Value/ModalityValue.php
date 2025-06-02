<?php

namespace LegitHealth\MedicalDevice\Response\Value;

enum ModalityValue: string
{
    case None = 'None';
    case Clinical = 'Clinical';
    case Dermoscopic = 'Dermoscopic';
}
