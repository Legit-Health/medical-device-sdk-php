<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\{SeverityAssessmentArguments};
use LegitHealth\MedicalDevice\Common\{BearerToken, Code};
use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\{BodySiteCode, KnownCondition, ScoringSystemCode, ScoringSystems, SevenPcQuestionnaire};
use LegitHealth\MedicalDevice\MedicalDeviceClient;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\{Intensity};
use DateTimeImmutable;
use Dotenv\Dotenv;
use Exception;
use LegitHealth\MedicalDevice\RequestException;
use PHPUnit\Framework\TestCase;

class SeverityAssessmentManualTest extends TestCase
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

    public function testSevenPc(): void
    {
        $sevenPcQuestionnaireValues = [
            "changeInSize" => 1,
            "irregularPigmentation" => 1,
            "irregularBorder" => 0,
            "inflammation" => 1,
            "itchOrAlteredSensation" => 0,
            "largerThanOtherLesions" => 0,
            "crustingOrBleeding" => 1
        ];
        $fileToUpload = $this->currentDir . '/tests/resources/nevus.jpg';
        $image = file_get_contents($fileToUpload);
        $sevenPcQuestionnaire = new SevenPcQuestionnaire(...$sevenPcQuestionnaireValues);
        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystem: new ScoringSystems([$sevenPcQuestionnaire]),
            knownCondition: new KnownCondition(Code::fromJson([
                "coding" => [
                    [
                        "system" => "https://icd.who.int/browse/2025-01/mms/en",
                        "systemDisplay" => "ICD-11",
                        "version" => "2025-01",
                        "code" => "2F20.Z",
                        "display" => "Melanocytic naevus, unspecified"
                    ]
                ],
                "text" => "Melanocytic naevus"
            ])),
            bodySiteCode: BodySiteCode::ArmLeft
        );
        try {
            $response = $this->medicalDeviceClient->severityAssessmentManual($severityAssessmentArguments, $this->bearerToken);
        } catch (RequestException $e) {
            var_dump($e->content);
        }

        $this->assertEquals('DiagnosticReport', $response->resourceType);
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertEquals('preliminary', $response->status);
        $this->assertEquals(
            (new DateTimeImmutable())->format('Ymd'),
            $response->issued->format('Ymd')
        );

        $sevenPc = $response->getPatientEvolutionInstance(ScoringSystemCode::SevenPc);
        $this->assertEquals(6, $sevenPc->score->value);
        $this->assertEquals('High risk', $sevenPc->score->interpretation->category);
        $this->assertEquals(Intensity::High, $sevenPc->score->interpretation->intensity);

        foreach ($sevenPc->items as $item) {
            $this->assertEquals($sevenPcQuestionnaireValues[$item->itemCode], $item->value);
            $this->assertNotEmpty($item->interpretation);
            $this->assertNotEmpty($item->code->text);
            $this->assertCount(1, $item->code->coding);
            $this->assertEquals($item->itemCode, $item->code->coding[0]->code);
        }
    }
}
