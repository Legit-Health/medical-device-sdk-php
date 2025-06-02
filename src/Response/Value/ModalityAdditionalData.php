<?php

namespace LegitHealth\MedicalDevice\Response\Value;

final readonly class ModalityAdditionalData
{
    public function __construct(
        public AdditionalDataItem $aiConfidenceClinical,
        public AdditionalDataItem $aiConfidenceDermoscopic
    ) {}
}
