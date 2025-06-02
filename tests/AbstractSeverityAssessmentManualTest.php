<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\Arguments\SeverityAssessmentManualArguments;
use LegitHealth\MedicalDevice\Common\{BearerToken, Code};
use LegitHealth\MedicalDevice\Arguments\Params\{BodySiteCode, KnownCondition, Questionnaire, ScoringSystems};
use LegitHealth\MedicalDevice\MedicalDeviceClient;
use stdClass;
use DateTimeImmutable;
use Dotenv\Dotenv;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractSeverityAssessmentManualTest extends TestCase
{
    private BearerToken $bearerToken;
    private string $currentDir;
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

    #[DataProvider('missingFieldProvider')]
    public function testMissingFieldsAreReported(string $path, array $expectedDetail): void
    {
        $args = $this->buildValidArguments();
        $this->removeFieldByDotPath($args, $path);
        $response = $this->httpClient->request(
            'POST',
            'severity-assessment/manual',
            [
                'json'    => $args,
                'headers' => ['Authorization' => $this->bearerToken->asAuthorizationHeader()],
            ]
        );

        $this->assertEquals(422, $response->getStatusCode());
        $detail = $response->toArray(false)['detail'];
        $this->assertCount(1, $detail);
        $this->assertEquals('missing', $detail[0]['type']);
        $this->assertEquals('Field required', $detail[0]['msg']);
        $this->assertEquals($expectedDetail['loc'], $detail[0]['loc']);
    }

    #[DataProvider('requestProvider')]
    public function testRequest(string $imagePath, Questionnaire $questionnaire, array $knownCondition, array $expectedValues): void
    {
        $image = file_get_contents($this->currentDir . $imagePath);
        $severityAssessmentArguments = new SeverityAssessmentManualArguments(
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

        $questionnaireValues = $questionnaire->jsonSerialize()['questionnaireResponse']['item'];
        foreach ($questionnaireResponse->item as $item) {
            $this->assertEquals($questionnaireValues[$item->itemCode], $item->value);
            $this->assertNotEmpty($item->interpretation);
            $this->assertNotEmpty($item->code->text);
            $this->assertCount(1, $item->code->coding);
            $this->assertEquals($item->itemCode, $item->code->coding[0]->code);
        }
    }

    /**
     * Merges the generic “always-required” fields with the questionnaire-specific ones.
     *
     * @return \Generator<string, array{0:string,1:array{loc: array<mixed>}}>
     */
    public static function requestProvider(): \Generator
    {
        foreach (static::getRequestValues() as $key => $arguments) {
            yield $key => $arguments;
        }
    }

    /**
     * Merges the generic “always-required” fields with the questionnaire-specific ones.
     *
     * @return \Generator<string, array{0:string,1:array{loc: array<mixed>}}>
     */
    public static function missingFieldProvider(): \Generator
    {
        yield 'bodySite is required' => [
            'bodySite',
            ['loc' => ['body', 'bodySite']],
        ];
        yield 'payload data is required' => [
            'payload.0.contentAttachment.data',
            ['loc' => ['body', 'payload', 0, 'contentAttachment', 'data']],
        ];
        yield 'knownCondition.conclusion.coding[0].system is required' => [
            'knownCondition.conclusion.coding.0.system',
            ['loc' => ['body', 'knownCondition', 'conclusion', 'coding', 0, 'system']],
        ];
        yield 'knownCondition.conclusion.coding[0].systemDisplay is required' => [
            'knownCondition.conclusion.coding.0.systemDisplay',
            ['loc' => ['body', 'knownCondition', 'conclusion', 'coding', 0, 'systemDisplay']],
        ];
        yield 'knownCondition.conclusion.coding[0].code is required' => [
            'knownCondition.conclusion.coding.0.code',
            ['loc' => ['body', 'knownCondition', 'conclusion', 'coding', 0, 'code']],
        ];
        yield 'knownCondition.conclusion.coding[0].display is required' => [
            'knownCondition.conclusion.coding.0.display',
            ['loc' => ['body', 'knownCondition', 'conclusion', 'coding', 0, 'display']],
        ];
        yield 'knownCondition.conclusion.text is required' => [
            'knownCondition.conclusion.text',
            ['loc' => ['body', 'knownCondition', 'conclusion', 'text']],
        ];

        foreach (static::getSpecificMissingFields() as $key => $cases) {
            yield $key => [
                $cases['path'],
                $cases['expectedDetail']
            ];
        }
    }

    private function buildValidArguments(): array
    {
        $image = file_get_contents($this->currentDir . '/tests/resources/nevus.jpg');

        $body = [
            'bodySite' => 'armRight',
            'payload' => [[
                'contentAttachment' => [
                    'data' => base64_encode($image),
                ],
            ]],
            'knownCondition' => [
                'conclusion' => [
                    'coding' => [[
                        'system' => 'https://icd.who.int/browse/2025-01/mms/en',
                        'systemDisplay' => 'ICD-11',
                        'version' => '2025-01',
                        'code' => '2C30',
                        'display' => 'Melanoma of skin',
                    ]],
                    'text' => 'Cutaneous melanoma',
                ],
            ],
            'scoringSystem'  => [
                static::questionnaireKey() => static::buildValidQuestionnaireResponse(),
            ],
        ];

        return $body;
    }

    /** Unset a nested array key by “dot path” */
    private function removeFieldByDotPath(array &$array, string $path): void
    {
        $keys = explode('.', $path);
        $ref = &$array;
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($ref[$key])) {
                return;
            }
            $ref = &$ref[$key];
        }
        unset($ref[array_shift($keys)]);
        if (\count($ref) === 0) {
            $ref = new stdClass();
        }
    }

    /**
     * @return array<string,array{path:string,loc:array<mixed>}>
     */
    abstract protected static function getRequestValues(): array;

    /**
     * @return array<string,array{path:string,loc:array<mixed>}>
     */
    abstract protected static function getSpecificMissingFields(): array;

    abstract protected static function questionnaireKey(): string;

    abstract protected static function buildValidQuestionnaireResponse(): mixed;
}
