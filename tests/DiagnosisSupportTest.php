<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\DiagnosisSupportArguments;
use LegitHealth\MedicalDevice\MedicalDeviceClient;
use DateTimeImmutable;
use Dotenv\Dotenv;
use LegitHealth\MedicalDevice\MedicalDeviceArguments\BearerToken;
use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\Subject;
use LegitHealth\MedicalDevice\MedicalDeviceArguments\RequestOptions;
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

    public function testBaseDiagnosisSupport()
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
            $response->effectiveDateTime->format('Ymd')
        );

        $clinicalIndicators = $response->clinicalIndicators;
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->hasCondition);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->malignancy);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->highPriorityReferral);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->pigmentedLesion);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->urgentReferral);

        $performanceIndicators = $response->performanceIndicators;
        $this->assertGreaterThan(0, $performanceIndicators->sensitivity);
        $this->assertGreaterThan(0, $performanceIndicators->specificity);
        $this->assertGreaterThan(0, $performanceIndicators->entropy);

        foreach ($response->imagingStudySeries as $imagingStudySeriesInstance) {
            $this->assertGreaterThan(0, count($imagingStudySeriesInstance->conclusions));
            $firstConclusion = $imagingStudySeriesInstance->conclusions[0];
            $this->assertNotEmpty($firstConclusion->conclusionCoding->code);
            $this->assertNotEmpty($firstConclusion->conclusionCoding->system);
            $this->assertNotEmpty($firstConclusion->conclusionCoding->systemAlias);
            $this->assertNotEmpty($firstConclusion->conclusionCoding->display);
            $this->assertNotEmpty($firstConclusion->probability);

            $media = $imagingStudySeriesInstance->media;
            $this->assertNotEmpty($media->modality);
            $this->assertTrue($media->validity->isValid);
            foreach ($media->validity->metrics as $validityMetric) {
                $this->assertTrue($validityMetric->isValid);
                $this->assertNotEmpty($validityMetric->name);
            }

            $this->assertGreaterThan(50.0, $media->validity->getDiqaScore());
            $this->assertNull($media->validity->getFailedValidityMetric());

            $performanceIndicators = $imagingStudySeriesInstance->performanceIndicators;
            $this->assertGreaterThan(0, $performanceIndicators->sensitivity);
            $this->assertGreaterThan(0, $performanceIndicators->specificity);
            $this->assertGreaterThan(0, $performanceIndicators->entropy);

            $clinicalIndicators = $imagingStudySeriesInstance->clinicalIndicators;
            $this->assertGreaterThanOrEqual(0, $clinicalIndicators->hasCondition);
            $this->assertGreaterThanOrEqual(0, $clinicalIndicators->malignancy);
            $this->assertGreaterThanOrEqual(0, $clinicalIndicators->highPriorityReferral);
            $this->assertGreaterThanOrEqual(0, $clinicalIndicators->pigmentedLesion);
            $this->assertGreaterThanOrEqual(0, $clinicalIndicators->urgentReferral);
        }

        $this->assertGreaterThan(0, count($response->conclusions));
        $firstConclusion = $response->conclusions[0];
        $this->assertNotEmpty($firstConclusion->conclusionCoding->code);
        $this->assertNotEmpty($firstConclusion->conclusionCoding->system);
        $this->assertNotEmpty($firstConclusion->conclusionCoding->systemAlias);
        $this->assertNotEmpty($firstConclusion->conclusionCoding->display);
        $this->assertNotEmpty($firstConclusion->probability);

        $this->assertGreaterThan(0, $response->analysisDuration);
    }

    public function testDiagnosisSupportWithSubject()
    {
        $fileToUpload1 = $this->currentDir . '/tests/resources/psoriasis_01.png';
        $image1 = file_get_contents($fileToUpload1);

        $fileToUpload2 = $this->currentDir . '/tests/resources/psoriasis_02.png';
        $image2 = file_get_contents($fileToUpload2);

        $fileToUpload3 = $this->currentDir . '/tests/resources/psoriasis_03.png';
        $image3 = file_get_contents($fileToUpload3);

        $diagnosisSupportArguments = new DiagnosisSupportArguments(
            subject: new Subject('xxx'),
            medias: [
                base64_encode($image1),
                base64_encode($image2),
                base64_encode($image3)
            ]
        );
        $response = $this->medicalDeviceClient->diagnosisSupport($diagnosisSupportArguments, $this->bearerToken);


        $this->assertEquals(
            (new DateTimeImmutable())->format('Ymd'),
            $response->effectiveDateTime->format('Ymd')
        );

        $clinicalIndicators = $response->clinicalIndicators;
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->hasCondition);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->malignancy);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->highPriorityReferral);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->pigmentedLesion);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->urgentReferral);

        $performanceIndicators = $response->performanceIndicators;
        $this->assertGreaterThan(0, $performanceIndicators->sensitivity);
        $this->assertGreaterThan(0, $performanceIndicators->specificity);
        $this->assertGreaterThan(0, $performanceIndicators->entropy);

        foreach ($response->imagingStudySeries as $imagingStudySeriesInstance) {
            $this->assertGreaterThan(0, count($imagingStudySeriesInstance->conclusions));
            $firstConclusion = $imagingStudySeriesInstance->conclusions[0];
            $this->assertNotEmpty($firstConclusion->conclusionCoding->code);
            $this->assertNotEmpty($firstConclusion->conclusionCoding->system);
            $this->assertNotEmpty($firstConclusion->conclusionCoding->systemAlias);
            $this->assertNotEmpty($firstConclusion->conclusionCoding->display);
            $this->assertNotEmpty($firstConclusion->probability);



            $media = $imagingStudySeriesInstance->media;
            $this->assertNotEmpty($media->modality);
            $this->assertTrue($media->validity->isValid);
            foreach ($media->validity->metrics as $validityMetric) {
                $this->assertTrue($validityMetric->isValid);
                $this->assertNotEmpty($validityMetric->name);
            }

            $this->assertGreaterThan(50.0, $media->validity->getDiqaScore());
            $this->assertNull($media->validity->getFailedValidityMetric());

            $performanceIndicators = $imagingStudySeriesInstance->performanceIndicators;
            $this->assertGreaterThan(0, $performanceIndicators->sensitivity);
            $this->assertGreaterThan(0, $performanceIndicators->specificity);
            $this->assertGreaterThan(0, $performanceIndicators->entropy);

            $clinicalIndicators = $imagingStudySeriesInstance->clinicalIndicators;
            $this->assertGreaterThanOrEqual(0, $clinicalIndicators->hasCondition);
            $this->assertGreaterThanOrEqual(0, $clinicalIndicators->malignancy);
            $this->assertGreaterThanOrEqual(0, $clinicalIndicators->highPriorityReferral);
            $this->assertGreaterThanOrEqual(0, $clinicalIndicators->pigmentedLesion);
            $this->assertGreaterThanOrEqual(0, $clinicalIndicators->urgentReferral);
        }

        $this->assertGreaterThan(0, count($response->conclusions));
        $firstConclusion = $response->conclusions[0];
        $this->assertNotEmpty($firstConclusion->conclusionCoding->code);
        $this->assertNotEmpty($firstConclusion->conclusionCoding->system);
        $this->assertNotEmpty($firstConclusion->conclusionCoding->systemAlias);
        $this->assertNotEmpty($firstConclusion->conclusionCoding->display);
        $this->assertNotEmpty($firstConclusion->probability);

        $this->assertGreaterThan(0, $response->analysisDuration);
    }

    public function testInvalidImage()
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

        $clinicalIndicators = $response->clinicalIndicators;
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->hasCondition);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->malignancy);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->highPriorityReferral);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->pigmentedLesion);
        $this->assertGreaterThanOrEqual(0, $clinicalIndicators->urgentReferral);

        $performanceIndicators = $response->performanceIndicators;
        $this->assertGreaterThan(0, $performanceIndicators->sensitivity);
        $this->assertGreaterThan(0, $performanceIndicators->specificity);
        $this->assertGreaterThan(0, $performanceIndicators->entropy);

        $this->assertGreaterThan(0, $response->analysisDuration);
        $failedMedias = $response->getIndexOfFailedMedias();
        $this->assertCount(2, $failedMedias);

        $this->assertEquals('quality', $failedMedias[0]->failedMetric->name);
        $this->assertEquals(1, $failedMedias[0]->index);

        $this->assertEquals('quality', $failedMedias[1]->failedMetric->name);
        $this->assertEquals(2, $failedMedias[1]->index);
    }

    public function testSendWithTimeout()
    {
        $fileToUpload1 = $this->currentDir . '/tests/resources/psoriasis_01.png';
        $image1 = file_get_contents($fileToUpload1);

        $diagnosisSupportArguments = new DiagnosisSupportArguments(
            subject: new Subject('xxx'),
            medias: [
                base64_encode($image1)
            ]
        );
        $response = $this->medicalDeviceClient->diagnosisSupport($diagnosisSupportArguments, $this->bearerToken, new RequestOptions(180));

        $this->assertEquals(
            (new DateTimeImmutable())->format('Ymd'),
            $response->effectiveDateTime->format('Ymd')
        );
    }
}
