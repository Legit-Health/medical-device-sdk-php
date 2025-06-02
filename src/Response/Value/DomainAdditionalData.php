<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class DomainAdditionalData
{
    public function __construct(
        public AdditionalDataItem $aiConfidence
    ) {}
}
