<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\{DiagnosisSupportArguments, BearerToken, RequestOptions};
use LegitHealth\MedicalDevice\MedicalDeviceClient;
use DateTimeImmutable;
use Dotenv\Dotenv;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\ModalityValue;
use PHPUnit\Framework\TestCase;

class DiagnosisSupportTest extends TestCase
{
    private MedicalDeviceClient $medicalDeviceClient;
    private BearerToken $bearerToken;
    private string $currentDir;

    public function setUp(): void
    {
        $this->currentDir = getcwd();
        $dotenv = Dotenv::createImmutable($this->currentDir, '.env.local');
        $dotenv->load();
        $this->medicalDeviceClient = MedicalDeviceClient::createWithBaseUri($_ENV['API_URL']);
        $accessToken = $this->medicalDeviceClient->login($_ENV['API_USERNAME'], $_ENV['API_PASSWORD']);
        $this->bearerToken = new BearerToken($accessToken->value);
    }

    public function testBaseDiagnosisSupport(): void
    {
        $fileToUpload1 = $this->currentDir . '/tests/resources/psoriasis_01.png';
        $image1 = file_get_contents($fileToUpload1);

        $fileToUpload2 = $this->currentDir . '/tests/resources/psoriasis_02.png';
        $image2 = file_get_contents($fileToUpload2);

        $fileToUpload3 = $this->currentDir . '/tests/resources/psoriasis_03.png';
        $image3 = file_get_contents($fileToUpload3);

        $diagnosisSupportArguments = new DiagnosisSupportArguments(
            medias: [
                base64_encode($image1),
                base64_encode($image2),
                base64_encode($image3)
            ]
        );
        $response = $this->medicalDeviceClient->diagnosisSupport($diagnosisSupportArguments, $this->bearerToken);

        $this->assertEquals(
            (new DateTimeImmutable())->format('Ymd'),
            $response->issued->format('Ymd')
        );

        $clinicalIndicator = $response->clinicalIndicator;
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->hasCondition);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->malignancy);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->highPriorityReferral);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->pigmentedLesion);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->urgentReferral);

        $performanceIndicator = $response->performanceIndicator;
        $this->assertGreaterThan(0, $performanceIndicator->sensitivity);
        $this->assertGreaterThan(0, $performanceIndicator->specificity);
        $this->assertGreaterThan(0, $performanceIndicator->entropy);

        foreach ($response->imagingAnalysis as $imagingAnalysisInstance) {
            $this->assertGreaterThan(0, count($imagingAnalysisInstance->conclusions));
            $firstConclusion = $imagingAnalysisInstance->conclusions[0];
            $this->assertNotEmpty($firstConclusion->code->code);
            $this->assertNotEmpty($firstConclusion->code->system);
            $this->assertNotEmpty($firstConclusion->code->systemAlias);
            $this->assertNotEmpty($firstConclusion->code->display);
            $this->assertNotEmpty($firstConclusion->probability);

            // Media validity
            $this->assertTrue($imagingAnalysisInstance->mediaValidity->isValid);
            $this->assertTrue($imagingAnalysisInstance->mediaValidity->quality->acceptable);
            $this->assertGreaterThan(0, $imagingAnalysisInstance->mediaValidity->quality->score);
            $this->assertNotEmpty($imagingAnalysisInstance->mediaValidity->quality->interpretation);
            $this->assertTrue($imagingAnalysisInstance->mediaValidity->domain->isDermatological);
            $this->assertGreaterThan(0, $imagingAnalysisInstance->mediaValidity->domain->aiConfidence->value);

            $this->assertEquals(ModalityValue::Clinical, $imagingAnalysisInstance->mediaValidity->modality->modality);
            $this->assertGreaterThan(0, $imagingAnalysisInstance->mediaValidity->modality->additionalData->aiConfidenceClinical);
            $this->assertGreaterThan(0, $imagingAnalysisInstance->mediaValidity->modality->additionalData->aiConfidenceDermoscopic);

            $performanceIndicator = $imagingAnalysisInstance->performanceIndicator;
            $this->assertGreaterThan(0, $performanceIndicator->entropy);

            $clinicalIndicator = $imagingAnalysisInstance->clinicalIndicator;
            $this->assertGreaterThanOrEqual(0, $clinicalIndicator->hasCondition);
            $this->assertGreaterThanOrEqual(0, $clinicalIndicator->malignancy);
            $this->assertGreaterThanOrEqual(0, $clinicalIndicator->highPriorityReferral);
            $this->assertGreaterThanOrEqual(0, $clinicalIndicator->pigmentedLesion);
            $this->assertGreaterThanOrEqual(0, $clinicalIndicator->urgentReferral);
        }

        $this->assertGreaterThan(0, count($response->conclusions));
        $firstConclusion = $response->conclusions[0];
        $this->assertNotEmpty($firstConclusion->code->code);
        $this->assertNotEmpty($firstConclusion->code->system);
        $this->assertNotEmpty($firstConclusion->code->systemAlias);
        $this->assertNotEmpty($firstConclusion->code->display);
        $this->assertNotEmpty($firstConclusion->probability);

        $this->assertGreaterThan(0, $response->analysisDuration);
    }

    public function testInvalidImage(): void
    {
        $fileToUpload1 = $this->currentDir . '/tests/resources/psoriasis_01.png';
        $fileToUpload2 = $this->currentDir . '/tests/resources/invalid.png';
        $fileToUpload3 = $this->currentDir . '/tests/resources/invalid.png';
        $diagnosisSupportArguments = new DiagnosisSupportArguments(
            medias: [
                base64_encode(file_get_contents($fileToUpload1)),
                base64_encode(file_get_contents($fileToUpload2)),
                base64_encode(file_get_contents($fileToUpload3)),
            ]
        );
        $response = $this->medicalDeviceClient->diagnosisSupport($diagnosisSupportArguments, $this->bearerToken);

        $clinicalIndicator = $response->clinicalIndicator;
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->hasCondition);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->malignancy);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->highPriorityReferral);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->pigmentedLesion);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->urgentReferral);

        $performanceIndicator = $response->performanceIndicator;
        $this->assertGreaterThan(0, $performanceIndicator->sensitivity);
        $this->assertGreaterThan(0, $performanceIndicator->specificity);
        $this->assertGreaterThan(0, $performanceIndicator->entropy);

        $this->assertGreaterThan(0, $response->analysisDuration);
        $failedMedias = $response->getIndexOfFailedMedias();
        $this->assertCount(2, $failedMedias);
    }

    public function testSendWithTimeout()
    {
        $fileToUpload1 = $this->currentDir . '/tests/resources/psoriasis_01.png';
        $image1 = file_get_contents($fileToUpload1);

        $diagnosisSupportArguments = new DiagnosisSupportArguments(
            medias: [
                base64_encode($image1)
            ]
        );
        $response = $this->medicalDeviceClient->diagnosisSupport($diagnosisSupportArguments, $this->bearerToken, new RequestOptions(180));

        $this->assertEquals(
            (new DateTimeImmutable())->format('Ymd'),
            $response->issued->format('Ymd')
        );
    }
}
