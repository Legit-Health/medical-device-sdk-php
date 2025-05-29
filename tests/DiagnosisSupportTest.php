<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceClient;
use LegitHealth\MedicalDevice\MedicalDeviceArguments\{DiagnosisSupportArguments, RequestOptions};
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\{AdditionalDataItem, ClinicalIndicator, Conclusion, Domain, Modality, ModalityValue, PerformanceIndicator, Quality};
use DateTimeImmutable;
use Dotenv\Dotenv;
use LegitHealth\MedicalDevice\Common\BearerToken;
use LegitHealth\MedicalDevice\RequestException;
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

    public function testDiagnosisSupportOfPsoriasis(): void
    {
        $this->assertDiagnosisSupport(
            ['/tests/resources/psoriasis_01.jpg', '/tests/resources/psoriasis_02.jpg', '/tests/resources/psoriasis_03.jpg'],
            ['text' => 'Psoriasis', 'code' => 'EA90', 'display' => 'Psoriasis']
        );
    }

    public function testDiagnosisSupportOfAcne(): void
    {
        $this->assertDiagnosisSupport(
            ['/tests/resources/acne.jpg'],
            ['text' => 'Acne', 'code' => 'ED80.Z', 'display' => 'Acne, unspecified']
        );
    }

    public function testInvalidImage(): void
    {
        $fileToUpload1 = $this->currentDir . '/tests/resources/psoriasis_01.jpg';
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
        $fileToUpload1 = $this->currentDir . '/tests/resources/psoriasis_01.jpg';
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

    public function testErrorWhenNotBase64()
    {
        $fileToUpload1 = $this->currentDir . '/tests/resources/psoriasis_01.jpg';
        $image1 = file_get_contents($fileToUpload1);

        $diagnosisSupportArguments = new DiagnosisSupportArguments(
            medias: [
                'data:image/jpeg;base64,' . base64_encode($image1)
            ]
        );
        try {
            $this->medicalDeviceClient->diagnosisSupport($diagnosisSupportArguments, $this->bearerToken);
        } catch (RequestException $requestException) {
            $this->assertEquals(422, $requestException->statusCode);
            $detail = $requestException->content['detail'];
            $this->assertCount(1, $detail);
            $this->assertEquals('value_error', $detail[0]['type']);
            $this->assertEquals('Value error, Only base64 data is allowed', $detail[0]['msg']);
            $this->assertEquals([
                "body",
                "payload",
                0,
                "contentAttachment",
                "data"
            ], $detail[0]['loc']);
        }
    }

    public function assertDiagnosisSupport(array $lesionImages, array $firstConclusionJson): void
    {
        $medias = [];
        foreach ($lesionImages as $lesionImage) {
            $fileToUpload = $this->currentDir . $lesionImage;
            $medias[] = base64_encode(file_get_contents($fileToUpload));
        }

        $diagnosisSupportArguments = new DiagnosisSupportArguments(medias: $medias);
        $response = $this->medicalDeviceClient->diagnosisSupport($diagnosisSupportArguments, $this->bearerToken);

        $this->assertEquals('DiagnosticReport', $response->resourceType);
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertEquals('preliminary', $response->status);
        $this->assertEquals(
            (new DateTimeImmutable())->format('Ymd'),
            $response->issued->format('Ymd')
        );
        $this->assertClinicalIndicator($response->clinicalIndicator);
        $this->assertPerformanceIndicator($response->performanceIndicator);

        $this->assertGreaterThan(0, count($response->conclusions));
        $firstConclusion = $response->conclusions[0];
        $this->assertConclusion($firstConclusion, $firstConclusionJson['text'], $firstConclusionJson['code'], $firstConclusionJson['display']);
        // to check that is really a percentage
        $this->assertGreaterThan(1, $firstConclusion->probability);

        foreach ($response->imagingAnalysis as $imagingAnalysisInstance) {

            // Media validity
            $this->assertTrue($imagingAnalysisInstance->mediaValidity->isValid);
            $this->assertMediaValidityQuality($imagingAnalysisInstance->mediaValidity->quality);
            $this->assertMediaValidityDomain($imagingAnalysisInstance->mediaValidity->domain);
            $this->assertMediaValidityModality($imagingAnalysisInstance->mediaValidity->modality);

            // to check that is really a percentage
            $this->assertGreaterThan(1, $imagingAnalysisInstance->performanceIndicator->entropy);

            $this->assertClinicalIndicator($imagingAnalysisInstance->clinicalIndicator);

            $this->assertGreaterThan(0, count($imagingAnalysisInstance->conclusions));
            $firstConclusion = $imagingAnalysisInstance->conclusions[0];
            $this->assertConclusion($firstConclusion, $firstConclusionJson['text'], $firstConclusionJson['code'], $firstConclusionJson['display']);

            foreach ($imagingAnalysisInstance->conclusions as $key => $conclusion) {
                if ($key >= 5) {
                    break;
                }
                $this->assertNotNull($conclusion->explainability);
                $this->assertNotNull($conclusion->explainability->heatMap);
                $this->assertNotEmpty($conclusion->explainability->heatMap->data);
                $this->assertNotEmpty($conclusion->explainability->heatMap->contentType);
            }
        }
    }

    private function assertPerformanceIndicator(PerformanceIndicator $performanceIndicator): void
    {
        // to check that is a percentage
        $this->assertGreaterThan(1, $performanceIndicator->sensitivity);
        $this->assertLessThanOrEqual(100, $performanceIndicator->sensitivity);
        $this->assertGreaterThan(1, $performanceIndicator->specificity);
        $this->assertLessThanOrEqual(100, $performanceIndicator->specificity);
        $this->assertLessThanOrEqual(100, $performanceIndicator->entropy);
        $this->assertGreaterThan(0, $performanceIndicator->entropy);
    }

    private function assertClinicalIndicator(ClinicalIndicator $clinicalIndicator): void
    {
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->hasCondition);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->malignancy);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->highPriorityReferral);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->pigmentedLesion);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicator->urgentReferral);
    }

    private function assertMediaValidityQuality(Quality $quality): void
    {
        $this->assertTrue($quality->acceptable);
        // to check that is really a percentage
        $this->assertGreaterThan(1, $quality->score);
        $this->assertNotEmpty($quality->interpretation);
    }

    private function assertMediaValidityDomain(Domain $domain): void
    {
        $this->assertTrue($domain->isDermatological);
        // to check that is really a percentage
        $this->assertGreaterThan(1, $domain->additionalData->aiConfidence->value);
        $this->assertAiConfidence($domain->additionalData->aiConfidence, 'aiConfidence', 'AI model confidence in the image being dermatological');
    }

    private function assertMediaValidityModality(Modality $modality): void
    {
        $this->assertEquals(ModalityValue::Clinical, $modality->value);
        $this->assertAiConfidence(
            $modality->additionalData->aiConfidenceClinical,
            'aiConfidenceClinical',
            'AI model confidence in identifying the image\'s modality as clinical'
        );
        $this->assertAiConfidence(
            $modality->additionalData->aiConfidenceDermoscopic,
            'aiConfidenceDermoscopic',
            'AI model confidence in identifying the image\'s modality as dermoscopic'
        );
    }

    private function assertAiConfidence(AdditionalDataItem $aiConfidence, string $code, string $text): void
    {
        $this->assertGreaterThanOrEqual(0, $aiConfidence->value);
        $this->assertLessThanOrEqual(100, $aiConfidence->value);
        $this->assertEquals($code, $aiConfidence->code->coding[0]->code);
        $this->assertEquals('Legit.Health', $aiConfidence->code->coding[0]->systemDisplay);
        $this->assertNull($aiConfidence->code->coding[0]->system);
        $this->assertNull($aiConfidence->code->coding[0]->version);
        $this->assertNull($aiConfidence->code->coding[0]->display);
        $this->assertEquals($text, $aiConfidence->code->text);
    }

    private function assertConclusion(Conclusion $conclusion, string $text, string $code, string $display): void
    {
        $this->assertEquals($text, $conclusion->code->text);
        $this->assertEquals($code, $conclusion->code->coding[0]->code);
        $this->assertEquals('https://icd.who.int/browse/2025-01/mms/en', $conclusion->code->coding[0]->system);
        $this->assertEquals('ICD-11', $conclusion->code->coding[0]->systemDisplay);
        $this->assertEquals('2025-01', $conclusion->code->coding[0]->version);
        $this->assertEquals($display, $conclusion->code->coding[0]->display);
        // to check that is really a percentage
        $this->assertGreaterThan(1, $conclusion->probability);
    }
}
