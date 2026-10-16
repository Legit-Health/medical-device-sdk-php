<?php

namespace LegitHealth\MedicalDevice\Tests;

use Dotenv\Dotenv;
use LegitHealth\MedicalDevice\Arguments\ImageValidationArguments;
use LegitHealth\MedicalDevice\Common\BearerToken;
use LegitHealth\MedicalDevice\MedicalDeviceClient;
use LegitHealth\MedicalDevice\RequestException;
use PHPUnit\Framework\TestCase;

class ImageValidationTest extends TestCase
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

    public function testValidAndInvalidImagesInRequestOrder(): void
    {
        $arguments = new ImageValidationArguments(
            medias: [
                $this->readImage('/tests/resources/psoriasis_01.jpg'),
                $this->readImage('/tests/resources/invalid.png'),
            ]
        );

        $response = $this->medicalDeviceClient->imageValidation($arguments, $this->bearerToken);

        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertCount(2, $response->imagingAnalysis);

        $validMedia = $response->imagingAnalysis[0]->mediaValidity;
        $this->assertTrue($validMedia->isValid);
        $this->assertTrue($validMedia->quality->acceptable);
        $this->assertTrue($validMedia->domain->isDermatological);

        $this->assertFalse($response->imagingAnalysis[1]->mediaValidity->isValid);

        $failedMedias = $response->getIndexOfFailedMedias();
        $this->assertCount(1, $failedMedias);
        $this->assertSame(1, $failedMedias[0]->index);
    }

    public function testUnauthorizedWithoutAValidToken(): void
    {
        $arguments = new ImageValidationArguments([$this->readImage('/tests/resources/psoriasis_01.jpg')]);

        try {
            $this->medicalDeviceClient->imageValidation($arguments, new BearerToken('invalid'));
            $this->fail('Expected a RequestException');
        } catch (RequestException $requestException) {
            $this->assertEquals(401, $requestException->statusCode);
        }
    }

    private function readImage(string $path): string
    {
        return base64_encode(file_get_contents($this->currentDir . $path));
    }
}
