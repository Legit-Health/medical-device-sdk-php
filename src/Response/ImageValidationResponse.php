<?php

namespace LegitHealth\MedicalDevice\Response;

use LegitHealth\MedicalDevice\Response\Value\{FailedMedia, ImageValidationInstance, MediaValidity};

final readonly class ImageValidationResponse
{
    /**
     * @param ImageValidationInstance[] $imagingAnalysis one entry per image, in request order
     */
    public function __construct(
        public array $imagingAnalysis,
        public float $analysisDuration
    ) {}

    public static function createFromJson(array $json): self
    {
        $imagingAnalysis = [];
        foreach ($json['imagingAnalysis'] as $imagingAnalysisRecord) {
            $imagingAnalysis[] = new ImageValidationInstance(
                MediaValidity::fromJson($imagingAnalysisRecord['mediaValidity'])
            );
        }

        return new self($imagingAnalysis, $json['analysisDuration']);
    }

    /**
     * @return FailedMedia[]
     */
    public function getIndexOfFailedMedias(): array
    {
        $indexes = [];
        foreach ($this->imagingAnalysis as $index => $imageValidationInstance) {
            if (!$imageValidationInstance->mediaValidity->isValid) {
                $indexes[] = new FailedMedia($index, $imageValidationInstance->mediaValidity);
            }
        }
        return $indexes;
    }
}
