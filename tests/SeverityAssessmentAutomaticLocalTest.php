<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\{BearerToken, SeverityAssessmentArguments};
use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\{
    ApasiLocalQuestionnaire,
    AscoradLocalQuestionnaire,
    AuasLocalQuestionnaire,
    BodySiteCode as ParamsBodySiteCode,
    DlqiQuestionnaire,
    GagsQuestionnaire,
    Ihs4LocalQuestionnaire,
    KnownCondition,
    PasiLocalQuestionnaire,
    PgaQuestionnaire,
    Pure4Questionnaire,
    Questionnaire,
    ScoringSystems,
    ResvechLocalQuestionnaire,
    ScoradLocalQuestionnaire,
    ScoringSystemCode,
    SevenPCQuestionnaire,
    SingleZoneApasiQuestionnaire,
    SingleZoneAuasQuestionnaire,
    UasLocalQuestionnaire,
    UctQuestionnaire
};
use Dotenv\Dotenv;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\DetectionLabel;
use LegitHealth\MedicalDevice\MedicalDeviceClient;
use PHPUnit\Framework\TestCase;

class SeverityAssessmentAutomaticLocalTest extends TestCase
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

    public function testSingleZoneApasi()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/psoriasis_02.png';
        $image = file_get_contents($fileToUpload);
        $apasiLocal = new SingleZoneApasiQuestionnaire(40);
        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystems: new ScoringSystems([$apasiLocal]),
            knownCondition: KnownCondition::fromIcd11('EA90', 'Psoriasis'),
            bodySiteCode: ParamsBodySiteCode::ArmLeft
        );
        $response = $this->medicalDeviceClient->severityAssessmentAutomaticLocal($severityAssessmentArguments, $this->bearerToken);

        $mediaValidity = $response->mediaValidity;
        $this->assertTrue($mediaValidity->isValid);
        $this->assertTrue($mediaValidity->quality->acceptable);
        $this->assertGreaterThan(0, $mediaValidity->quality->score);
        $this->assertNotEmpty($mediaValidity->quality->interpretation);
        $this->assertTrue($mediaValidity->domain->isDermatological);
        $this->assertGreaterThan(0, $mediaValidity->domain->aiConfidence->value);
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertCount(1, $response->patientEvolution);

        // APASI
        $apasiValue = $response->getPatientEvolutionInstance(ScoringSystemCode::Apasi);
        $this->assertGreaterThanOrEqual(0, $apasiValue->score->value);
        $this->assertNotNull($apasiValue->score->interpretation);
        $this->assertThat(
            $apasiValue->getEvolutionItem('desquamation')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );
        $this->assertGreaterThanOrEqual(50, $apasiValue->getEvolutionItem('desquamation')->additionalData['aiConfidence']['value']);
        $this->assertThat(
            $apasiValue->getEvolutionItem('erythema')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );
        $this->assertGreaterThanOrEqual(50, $apasiValue->getEvolutionItem('erythema')->additionalData['aiConfidence']['value']);
        $this->assertThat(
            $apasiValue->getEvolutionItem('induration')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );
        $this->assertGreaterThanOrEqual(50, $apasiValue->getEvolutionItem('induration')->additionalData['aiConfidence']['value']);
        $this->assertEquals(
            3,
            $apasiValue->getEvolutionItem('surface')->value,
        );
        $this->assertCount(3, $apasiValue->attachments);
    }

    public function testSingleZoneAuas()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/urticaria.jpg';
        $image = file_get_contents($fileToUpload);
        $auasQuestionnaire = new SingleZoneAuasQuestionnaire(1);
        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystems: new ScoringSystems([$auasQuestionnaire]),
            knownCondition: KnownCondition::fromIcd11('EB05', 'Urticaria'),
            bodySiteCode: ParamsBodySiteCode::ArmLeft
        );
        $response = $this->medicalDeviceClient->severityAssessmentAutomaticLocal($severityAssessmentArguments, $this->bearerToken);

        $mediaValidity = $response->mediaValidity;
        $this->assertTrue($mediaValidity->isValid);
        $this->assertTrue($mediaValidity->quality->acceptable);
        $this->assertGreaterThan(0, $mediaValidity->quality->score);
        $this->assertNotEmpty($mediaValidity->quality->interpretation);
        $this->assertTrue($mediaValidity->domain->isDermatological);
        $this->assertGreaterThan(0, $mediaValidity->domain->aiConfidence->value);
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertCount(1, $response->patientEvolution);

        // Auas
        $auasValue = $response->getPatientEvolutionInstance(ScoringSystemCode::Auas);
        $this->assertGreaterThanOrEqual(0, $auasValue->score->value);
        $this->assertNotNull($auasValue->score->interpretation);
        $this->assertThat(
            $auasValue->getEvolutionItem('wheals')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );
        $this->assertGreaterThanOrEqual(30, $auasValue->getEvolutionItem('wheals')->additionalData['whealsCount']['value']);
        $this->assertThat(
            $auasValue->getEvolutionItem('pruritus')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );
        $this->assertEquals(
            $auasValue->getEvolutionItem('wheals')->additionalData['whealsCount']['value'],
            \count($auasValue->detections)
        );
    }
}
