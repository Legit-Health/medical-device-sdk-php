<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceClient;
use Dotenv\Dotenv;
use LegitHealth\MedicalDevice\LoginException;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private MedicalDeviceClient $medicalDeviceClient;
    private string $currentDir;

    public function setUp(): void
    {
        $this->currentDir = getcwd();
        $dotenv = Dotenv::createImmutable($this->currentDir, '.env.local');
        $dotenv->load();
    }

    public function testLoginFailedWithNoEmail(): void
    {
        $this->expectException(LoginException::class);
        $this->medicalDeviceClient = MedicalDeviceClient::createWithBaseUri($_ENV['API_URL']);
        $this->medicalDeviceClient->login('foo', 'bar');
    }

    public function testLoginFailedWithEmail(): void
    {
        $this->expectException(LoginException::class);
        $this->medicalDeviceClient = MedicalDeviceClient::createWithBaseUri($_ENV['API_URL']);
        $this->medicalDeviceClient->login('foo@bar.com', 'zeta');
    }

    public function testLoginSuccess(): void
    {
        $this->medicalDeviceClient = MedicalDeviceClient::createWithBaseUri($_ENV['API_URL']);
        $accessToken = $this->medicalDeviceClient->login($_ENV['API_USERNAME'], $_ENV['API_PASSWORD']);
        $this->assertGreaterThan(0, $accessToken->expiresInMinutes);
        $this->assertEquals('Bearer token', $accessToken->tokenType);
    }
}
