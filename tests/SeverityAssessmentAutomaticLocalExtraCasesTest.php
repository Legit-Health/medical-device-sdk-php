<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\Common\BearerToken;
use LegitHealth\MedicalDevice\Arguments\Params\{NsilQuestionnaire};
use LegitHealth\MedicalDevice\MedicalDeviceClient;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SeverityAssessmentAutomaticLocalExtraCasesTest extends TestCase
{
    private BearerToken $bearerToken;
    protected string $currentDir;
    private HttpClientInterface $httpClient;
    private MedicalDeviceClient $medicalDeviceClient;

    public function setUp(): void
    {
        $this->currentDir = getcwd();
        Dotenv::createImmutable($this->currentDir, '.env.local')->load();

        $client = MedicalDeviceClient::createWithBaseUri($_ENV['API_URL']);
        $accessToken = $client->login($_ENV['API_USERNAME'], $_ENV['API_PASSWORD']);
        $this->bearerToken = new BearerToken($accessToken->value);
        $this->httpClient  = HttpClient::createForBaseUri($_ENV['API_URL']);
        $this->medicalDeviceClient = MedicalDeviceClient::createWithBaseUri($_ENV['API_URL']);
    }

    public function testAllowNullInConclusionCoding()
    {
        $body = [
            'bodySite' => 'armRight',
            'payload' => [
                'contentAttachment' => [
                    'data' => base64_encode(file_get_contents($this->currentDir . '/tests/resources/non_specific.jpg')),
                ],
            ],
            'knownCondition' => [
                'conclusion' => [
                    'coding' => null,
                    'text' => "Non-specific finding",
                ]
            ],
            'scoringSystem'  => [
                NsilQuestionnaire::getName() => new NsilQuestionnaire()->jsonSerialize()
            ],
        ];
        $response = $this->httpClient->request(
            'POST',
            'severity-assessment/automatic/local',
            [
                'json'    => $body,
                'headers' => ['Authorization' => $this->bearerToken->asAuthorizationHeader()],
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testErrorWhenNotBase64()
    {
        $body = [
            'bodySite' => 'armRight',
            'payload' => [
                'contentAttachment' => [
                    'data' => 'data:image/jpeg;base64,' . base64_encode(file_get_contents($this->currentDir . '/tests/resources/non_specific.jpg'))
                ],
            ],
            'knownCondition' => [
                'conclusion' => [
                    'coding' => null,
                    'text' => "Non-specific finding",
                ]
            ],
            'scoringSystem'  => [
                NsilQuestionnaire::getName() => new NsilQuestionnaire()->jsonSerialize()
            ],
        ];
        $response = $this->httpClient->request(
            'POST',
            'severity-assessment/automatic/local',
            [
                'json'    => $body,
                'headers' => ['Authorization' => $this->bearerToken->asAuthorizationHeader()],
            ]
        );
        $this->assertEquals(422, $response->getStatusCode());

        $detail = $response->toArray(false)['detail'];
        $this->assertCount(1, $detail);
        $this->assertEquals('value_error', $detail[0]['type']);
        $this->assertEquals('Value error, Only base64 data is allowed', $detail[0]['msg']);
        $this->assertEquals([
            "body",
            "payload",
            "contentAttachment",
            "data"
        ], $detail[0]['loc']);
    }
}
