<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\{SeverityAssessmentArguments};
use LegitHealth\MedicalDevice\Common\{BearerToken, Code};
use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\{BodySiteCode, DlqiQuestionnaire, KnownCondition, Pure4Questionnaire, Questionnaire, ScoringSystems, SevenPcQuestionnaire};
use LegitHealth\MedicalDevice\MedicalDeviceClient;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\Intensity;
use DateTimeImmutable;
use Dotenv\Dotenv;
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
        $sevenPcQuestionnaire = new SevenPcQuestionnaire(...$sevenPcQuestionnaireValues);

        $this->assertSeverityAssessmentQuestionnaire(
            '/tests/resources/nevus.jpg',
            $sevenPcQuestionnaire,
            ["code" => "2F20.Z", "display" => "Melanocytic naevus, unspecified", "text" => "Melanocytic naevus"],
            ['scoreValue' => 6, 'interpretationCategory' => 'High risk', 'intensity' => Intensity::High]
        );
    }

    public function testDlqi(): void
    {
        $this->assertSeverityAssessmentQuestionnaire(
            '/tests/resources/nevus.jpg',
            new DlqiQuestionnaire(1, 2, 3, 1, 2, 3, 1, 2, 3, 1),
            ["code" => "2F20.Z", "display" => "Melanocytic naevus, unspecified", "text" => "Melanocytic naevus"],
            ['scoreValue' => 19, 'interpretationCategory' => 'Very large effect', 'intensity' => Intensity::High]
        );
    }

    public function testPure4(): void
    {
        $this->assertSeverityAssessmentQuestionnaire(
            '/tests/resources/nevus.jpg',
            new Pure4Questionnaire(1, 0, 1, 0),
            ["code" => "2F20.Z", "display" => "Melanocytic naevus, unspecified", "text" => "Melanocytic naevus"],
            ['scoreValue' => 2, 'interpretationCategory' => 'Positive', 'intensity' => Intensity::High]
        );
    }

    public function assertSeverityAssessmentQuestionnaire(string $imagePath, Questionnaire $questionnaire, array $knownCondition, array $expectedValues): void
    {
        $image = file_get_contents($this->currentDir . $imagePath);
        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystem: new ScoringSystems([$questionnaire]),
            knownCondition: new KnownCondition(Code::fromJson([
                "coding" => [
                    [
                        "system" => "https://icd.who.int/browse/2025-01/mms/en",
                        "systemDisplay" => "ICD-11",
                        "version" => "2025-01",
                        "code" => $knownCondition['code'],
                        "display" => $knownCondition['display']
                    ]
                ],
                "text" => $knownCondition['text']
            ])),
            bodySiteCode: BodySiteCode::ArmLeft
        );
        $response = $this->medicalDeviceClient->severityAssessmentManual($severityAssessmentArguments, $this->bearerToken);

        $this->assertEquals('DiagnosticReport', $response->resourceType);
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertEquals('preliminary', $response->status);
        $this->assertEquals(
            (new DateTimeImmutable())->format('Ymd'),
            $response->issued->format('Ymd')
        );

        $questionnaireResponse = $response->getPatientEvolutionInstance($questionnaire::getName());
        $this->assertEquals($expectedValues['scoreValue'], $questionnaireResponse->score->value);
        $this->assertEquals($expectedValues['interpretationCategory'], $questionnaireResponse->score->interpretation->category);
        $this->assertEquals($expectedValues['intensity'], $questionnaireResponse->score->interpretation->intensity);

        $questionnaireValues = $questionnaire->asArray()['item'];
        foreach ($questionnaireResponse->items as $item) {
            $this->assertEquals($questionnaireValues[$item->itemCode], $item->value);
            $this->assertNotEmpty($item->interpretation);
            $this->assertNotEmpty($item->code->text);
            $this->assertCount(1, $item->code->coding);
            $this->assertEquals($item->itemCode, $item->code->coding[0]->code);
        }
    }
}
