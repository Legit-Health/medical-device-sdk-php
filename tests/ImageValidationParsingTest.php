<?php

namespace LegitHealth\MedicalDevice\Tests;

use InvalidArgumentException;
use LegitHealth\MedicalDevice\Arguments\ImageValidationArguments;
use LegitHealth\MedicalDevice\Response\ImageValidationResponse;
use PHPUnit\Framework\TestCase;

class ImageValidationParsingTest extends TestCase
{
    public function testParsesOneEntryPerImageInRequestOrder(): void
    {
        $response = ImageValidationResponse::createFromJson([
            'analysisDuration' => 0.6,
            'imagingAnalysis' => [
                ['mediaValidity' => $this->mediaValidityJson(true)],
                ['mediaValidity' => $this->mediaValidityJson(false)],
            ],
        ]);

        $this->assertSame(0.6, $response->analysisDuration);
        $this->assertCount(2, $response->imagingAnalysis);
        $this->assertTrue($response->imagingAnalysis[0]->mediaValidity->isValid);
        $this->assertSame(7.5, $response->imagingAnalysis[0]->mediaValidity->quality->score);
        $this->assertFalse($response->imagingAnalysis[1]->mediaValidity->isValid);
        $this->assertFalse($response->imagingAnalysis[1]->mediaValidity->domain->isDermatological);

        $failedMedias = $response->getIndexOfFailedMedias();
        $this->assertCount(1, $failedMedias);
        $this->assertSame(1, $failedMedias[0]->index);
    }

    public function testSerializesTheSameBodyAsDiagnosisSupport(): void
    {
        $this->assertSame(
            ['payload' => [['contentAttachment' => ['data' => 'aW1hZ2U=']]]],
            (new ImageValidationArguments(['aW1hZ2U=']))->jsonSerialize()
        );
    }

    public function testMoreThanFiveImagesAreRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ImageValidationArguments(array_fill(0, 6, 'aW1hZ2U='));
    }

    public function testNoImagesAreRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ImageValidationArguments([]);
    }

    private function mediaValidityJson(bool $isValid): array
    {
        $item = fn(string $text) => ['value' => 50.0, 'code' => ['text' => $text], 'interpretation' => $text];
        return [
            'quality' => ['acceptable' => $isValid, 'score' => $isValid ? 7.5 : 0.25, 'interpretation' => $isValid ? 'good' : 'poor'],
            'domain' => ['isDermatological' => $isValid, 'additionalData' => ['aiConfidence' => $item('dermatological')]],
            'modality' => [
                'value' => $isValid ? 'Clinical' : 'None',
                'additionalData' => ['aiConfidenceClinical' => $item('pass'), 'aiConfidenceDermoscopic' => $item('fail')],
            ],
            'isValid' => $isValid,
        ];
    }
}
