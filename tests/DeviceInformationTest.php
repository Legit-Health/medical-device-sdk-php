<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceClient;
use Dotenv\Dotenv;
use LegitHealth\MedicalDevice\Common\BearerToken;
use PHPUnit\Framework\TestCase;

class DeviceInformationTest extends TestCase
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


    public function testSuccess(): void
    {
        $this->medicalDeviceClient = MedicalDeviceClient::createWithBaseUri($_ENV['API_URL']);
        $response = $this->medicalDeviceClient->deviceInformation($this->bearerToken);
        $this->assertEquals('AI LABS GROUP S.L.', $response['manufacturer']['name']);
        $this->assertEquals(
            'Street Gran Vía 1, BAT Tower, 48001, Bilbao, Bizkaia (Spain)',
            $response['manufacturer']['address']
        );
        $this->assertEquals(
            '1.1.0.0',
            $response['version'][0]['value']
        );
        $this->assertEquals(
            'Legit.Health Plus',
            $response['name'][0]['value']
        );
    }
}
