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
    SingleZoneAeasiQuestionnaire,
    SingleZoneAgppgaQuestionnaire,
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

    public function testSingleZoneAesi()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/eczema.jpg';
        $image = file_get_contents($fileToUpload);
        $aesiQuestionnaire = new SingleZoneAeasiQuestionnaire(30, 27);
        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystems: new ScoringSystems([$aesiQuestionnaire]),
            knownCondition: KnownCondition::fromIcd11('EA89', 'Eczematous dermatitis'),
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
        $this->assertGreaterThan(0, $mediaValidity->modality->additionalData->aiConfidenceClinical);
        $this->assertGreaterThan(0, $mediaValidity->modality->additionalData->aiConfidenceDermoscopic);
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertCount(1, $response->patientEvolution);

        $aesiValue = $response->getPatientEvolutionInstance(ScoringSystemCode::Aeasi);
        $this->assertGreaterThanOrEqual(0, $aesiValue->score->value);
        $this->assertNotEmpty($aesiValue->score->interpretation->category);
        $this->assertGreaterThanOrEqual(0, $aesiValue->score->globalScoreContribution->value);

        $this->assertEquals(
            3,
            $aesiValue->getEvolutionItem('surface')->value,
        );
        $this->assertGreaterThanOrEqual(0, count($aesiValue->getEvolutionItem('surface')->code->coding));

        $this->assertThat(
            $aesiValue->getEvolutionItem('redness')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );
        $this->assertGreaterThanOrEqual(0, count($aesiValue->getEvolutionItem('redness')->code->coding));
        $this->assertGreaterThanOrEqual(50, $aesiValue->getEvolutionItem('redness')->additionalData['aiConfidence']['value']);

        $this->assertThat(
            $aesiValue->getEvolutionItem('thickness')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );
        $this->assertGreaterThanOrEqual(0, count($aesiValue->getEvolutionItem('thickness')->code->coding));
        $this->assertGreaterThanOrEqual(50, $aesiValue->getEvolutionItem('thickness')->additionalData['aiConfidence']['value']);

        $this->assertThat(
            $aesiValue->getEvolutionItem('scratching')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );
        $this->assertGreaterThanOrEqual(0, count($aesiValue->getEvolutionItem('scratching')->code->coding));
        $this->assertGreaterThanOrEqual(50, $aesiValue->getEvolutionItem('scratching')->additionalData['aiConfidence']['value']);

        $this->assertThat(
            $aesiValue->getEvolutionItem('lichenification')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );
        $this->assertGreaterThanOrEqual(0, count($aesiValue->getEvolutionItem('lichenification')->code->coding));
        $this->assertGreaterThanOrEqual(50, $aesiValue->getEvolutionItem('lichenification')->additionalData['aiConfidence']['value']);

        $this->assertCount(3, $aesiValue->attachments);
        $this->assertNotNull(array_find($aesiValue->attachments, fn($attachment) => $attachment->code === 'maskRaw'));
        $this->assertNotNull(array_find($aesiValue->attachments, fn($attachment) => $attachment->code === 'maskBinary'));
        $this->assertNotNull(array_find($aesiValue->attachments, fn($attachment) => $attachment->code === 'segmentation'));
    }


    public function testSingleZoneAgppga()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/pustular_psoriasis.jpg';
        $image = file_get_contents($fileToUpload);
        $agppgaQuestionnaire = new SingleZoneAgppgaQuestionnaire();
        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystems: new ScoringSystems([$agppgaQuestionnaire]),
            knownCondition: KnownCondition::fromIcd11('EA90.4', 'Pustular psoriasis'),
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

        $agppgaValue = $response->getPatientEvolutionInstance(ScoringSystemCode::Agppga);
        $this->assertGreaterThanOrEqual(0, $agppgaValue->score->value);
        $this->assertNotNull($agppgaValue->score->interpretation);
        $this->assertThat(
            $agppgaValue->getEvolutionItem('erythema')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );
        $this->assertGreaterThanOrEqual(0, count($agppgaValue->getEvolutionItem('erythema')->code->coding));
        $this->assertNull($agppgaValue->getEvolutionItem('erythema')->interpretation);

        $this->assertThat(
            $agppgaValue->getEvolutionItem('desquamation')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );
        $this->assertGreaterThanOrEqual(0, count($agppgaValue->getEvolutionItem('desquamation')->code->coding));
        $this->assertNull($agppgaValue->getEvolutionItem('desquamation')->interpretation);

        $this->assertThat(
            $agppgaValue->getEvolutionItem('pustulation')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );
        $this->assertGreaterThanOrEqual(0, count($agppgaValue->getEvolutionItem('pustulation')->code->coding));
        $this->assertNull($agppgaValue->getEvolutionItem('pustulation')->interpretation);

        $this->assertCount(0, $agppgaValue->attachments);
    }


    public function testSingleZoneApasi()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/psoriasis_02.png';
        $image = file_get_contents($fileToUpload);
        $apasiQuestionnaire = new SingleZoneApasiQuestionnaire(40);
        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystems: new ScoringSystems([$apasiQuestionnaire]),
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
        $this->assertGreaterThan(0, $mediaValidity->domain->aiConfidence->value);
        $this->assertGreaterThan(0, $mediaValidity->modality->additionalData->aiConfidenceClinical);
        $this->assertGreaterThan(0, $mediaValidity->modality->additionalData->aiConfidenceDermoscopic);
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertCount(1, $response->patientEvolution);

        // APASI
        $apasiValue = $response->getPatientEvolutionInstance(ScoringSystemCode::Apasi);
        $this->assertGreaterThanOrEqual(0, $apasiValue->score->value);
        $this->assertNotNull($apasiValue->score->interpretation);
        $this->assertGreaterThanOrEqual(0, $apasiValue->score->globalScoreContribution->value);
        $this->assertThat(
            $apasiValue->getEvolutionItem('desquamation')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );
        $this->assertGreaterThanOrEqual(0, count($apasiValue->getEvolutionItem('desquamation')->code->coding));
        $this->assertGreaterThanOrEqual(50, $apasiValue->getEvolutionItem('desquamation')->additionalData['aiConfidence']['value']);
        $this->assertThat(
            $apasiValue->getEvolutionItem('erythema')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );
        $this->assertGreaterThanOrEqual(0, count($apasiValue->getEvolutionItem('erythema')->code->coding));
        $this->assertGreaterThanOrEqual(50, $apasiValue->getEvolutionItem('erythema')->additionalData['aiConfidence']['value']);
        $this->assertThat(
            $apasiValue->getEvolutionItem('induration')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );
        $this->assertGreaterThanOrEqual(0, count($apasiValue->getEvolutionItem('induration')->code->coding));
        $this->assertGreaterThanOrEqual(50, $apasiValue->getEvolutionItem('induration')->additionalData['aiConfidence']['value']);
        $this->assertEquals(
            3,
            $apasiValue->getEvolutionItem('surface')->value,
        );
        $this->assertCount(3, $apasiValue->attachments);
        $this->assertNotNull(array_find($apasiValue->attachments, fn($attachment) => $attachment->code === 'maskRaw'));
        $this->assertNotNull(array_find($apasiValue->attachments, fn($attachment) => $attachment->code === 'maskBinary'));
        $this->assertNotNull(array_find($apasiValue->attachments, fn($attachment) => $attachment->code === 'segmentation'));
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
        $this->assertGreaterThanOrEqual(0, count($auasValue->getEvolutionItem('wheals')->code->coding));
        $this->assertNotEmpty($auasValue->getEvolutionItem('wheals')->interpretation);
        $this->assertEquals(
            $auasValue->getEvolutionItem('wheals')->additionalData['whealsCount']['value'],
            \count($auasValue->detections)
        );
        foreach ($auasValue->detections as $detection) {
            $this->assertGreaterThan(0, $detection->confidence);
            $this->assertGreaterThanOrEqual(0, $detection->box->p1->x);
            $this->assertGreaterThanOrEqual(0, $detection->box->p1->y);
            $this->assertGreaterThanOrEqual(0, $detection->box->p2->x);
            $this->assertGreaterThanOrEqual(0, $detection->box->p2->y);
        }

        $this->assertNotEmpty($auasValue->getEvolutionItem('pruritus')->interpretation);
        $this->assertGreaterThanOrEqual(0, count($auasValue->getEvolutionItem('pruritus')->code->coding));
        $this->assertThat(
            $auasValue->getEvolutionItem('pruritus')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertCount(1, $auasValue->attachments);
        $this->assertNotNull(array_find($auasValue->attachments, fn($attachment) => $attachment->code === 'annotation'));
    }
}
