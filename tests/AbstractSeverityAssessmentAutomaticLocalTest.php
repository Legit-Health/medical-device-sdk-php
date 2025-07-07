<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\Arguments\SeverityAssessmentAutomaticLocalArguments;
use LegitHealth\MedicalDevice\Common\{BearerToken, Code};
use LegitHealth\MedicalDevice\Arguments\Params\{BodySiteCode, KnownCondition, Questionnaire, ScoringSystems};
use LegitHealth\MedicalDevice\MedicalDeviceClient;
use stdClass;
use DateTimeImmutable;
use Dotenv\Dotenv;
use LegitHealth\MedicalDevice\RequestException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractSeverityAssessmentAutomaticLocalTest extends TestCase
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

    #[DataProvider('missingFieldProvider')]
    public function testMissingFieldsAreReported(string $path, array $expectedDetail): void
    {
        $args = $this->buildValidArguments($this->currentDir);
        $this->removeFieldByDotPath($args, $path);
        $response = $this->httpClient->request(
            'POST',
            'severity-assessment/automatic/local',
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
    public function testRequest(
        string $imagePath,
        Questionnaire $questionnaire,
        array $knownCondition,
        ?array $expectedValues,
        ?BodySiteCode $bodySiteCode = null,
        ?int $statusCode = null
    ): void {
        $image = file_get_contents($this->currentDir . $imagePath);
        $severityAssessmentArguments = new SeverityAssessmentAutomaticLocalArguments(
            base64_encode($image),
            scoringSystem: new ScoringSystems([$questionnaire]),
            knownCondition: new KnownCondition(Code::fromJson([
                "coding" => $knownCondition['code'] === null ? null : [
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
            bodySiteCode: $bodySiteCode ?? BodySiteCode::ArmLeft
        );
        try {
            $response = $this->medicalDeviceClient->severityAssessmentAutomaticLocal($severityAssessmentArguments, $this->bearerToken);
        } catch (RequestException $requestException) {
            $this->assertEquals($statusCode, $requestException->statusCode);
            return;
        }

        $this->assertEquals('DiagnosticReport', $response->resourceType);
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertEquals('preliminary', $response->status);
        $this->assertEquals(
            (new DateTimeImmutable())->format('Ymd'),
            $response->issued->format('Ymd')
        );

        $questionnaireResponse = $response->getPatientEvolutionInstance($questionnaire::getName());
        $expectedValues['scoreValue']($questionnaireResponse->score->value);
        $this->assertEquals($expectedValues['interpretationCategory'], $questionnaireResponse->score->interpretation->category);
        $this->assertEquals($expectedValues['intensity'], $questionnaireResponse->score->interpretation->intensity);

        if ($expectedValues['item'] === null) {
            $this->assertNull($questionnaireResponse->item);
        } else {
            foreach ($expectedValues['item'] as $itemCode => $itemValue) {
                $questionnaireResponseItem = $questionnaireResponse->getEvolutionItem($itemCode);
                if (is_callable($itemValue['value'])) {
                    $itemValue['value']($questionnaireResponseItem->value);
                } else {
                    $this->assertEquals($itemValue['value'], $questionnaireResponseItem->value);
                }
                $this->assertEquals($itemValue['interpretation'] ?? null, $questionnaireResponseItem->interpretation);
                if (isset($itemValue['additionalData'])) {
                    foreach ($itemValue['additionalData'] as $additionalDataCode => $additionalDataExpected) {
                        $additionalDataValue = $questionnaireResponseItem->additionalData[$additionalDataCode];
                        $this->assertEquals($additionalDataExpected['text'], $additionalDataValue->code->text);
                        $this->assertEquals($additionalDataExpected['code'], $additionalDataValue->code->coding[0]->code);
                        if (isset($additionalDataExpected['interpretation'])) {
                            $this->assertEquals($additionalDataExpected['interpretation'], $additionalDataValue->interpretation);
                        }

                        if ($additionalDataExpected['typeOfValue'] === 'scalar') {
                            $this->assertGreaterThanOrEqual(0, $additionalDataValue->value);
                        } elseif ($additionalDataExpected['typeOfValue'] === 'percentage') {
                            $this->assertGreaterThanOrEqual(0, $additionalDataValue->value);
                            $this->assertLessThanOrEqual(100, $additionalDataValue->value);
                        }
                    }
                }
                $this->assertEquals($itemValue['text'], $questionnaireResponseItem->code->text);
                $this->assertCount(1, $questionnaireResponseItem->code->coding);
                $this->assertEquals($itemCode, $questionnaireResponseItem->code->coding[0]->code);
            }
        }
        if ($expectedValues['attachment'] === null) {
            $this->assertNull($questionnaireResponse->media->attachment);
        } else {
            $this->assertCount(\count($expectedValues['attachment']), $questionnaireResponse->media->attachment);
            foreach ($expectedValues['attachment'] as $attachmentCode => $expectedAttachment) {
                $attachment = $questionnaireResponse->media->attachment[$attachmentCode] ?? null;
                $this->assertNotNull($attachment);
                $this->assertEquals($attachmentCode, $attachment->code);
                $this->assertEquals($expectedAttachment['title'], $attachment->title);
                $this->assertEquals($expectedAttachment['width'], $attachment->width);
                $this->assertEquals($expectedAttachment['height'], $attachment->height);
                $this->assertNotEmpty($attachment->data);
                $this->assertEquals('image/jpeg', $attachment->contentType);
                $this->assertEquals('RGB', $attachment->colorModel);
            }
        }

        if (isset($expectedValues['detection'])) {
            $expectedCodes = array_keys($expectedValues['detection']);
            $expectedTexts = array_values($expectedValues['detection']);
            $this->assertGreaterThan(0, $questionnaireResponse->media->detection);
            $detection = $questionnaireResponse->media->detection[0];
            $this->assertGreaterThan(0, $detection->confidence);
            $this->assertGreaterThan(0, $detection->box->x1);
            $this->assertGreaterThan(0, $detection->box->y1);
            $this->assertGreaterThan(0, $detection->box->x2);
            $this->assertGreaterThan(0, $detection->box->y2);
            $this->assertContains($detection->code->text, $expectedTexts);
            $this->assertContains($detection->code->coding[0]->code, $expectedCodes);
        }

        if (isset($expectedValues['globalScoreContribution'])) {
            $expectedValues['globalScoreContribution']($questionnaireResponse->score->globalScoreContribution->value);
            $this->assertEquals('Contribution of the body zone score to the full-body score', $questionnaireResponse->score->globalScoreContribution->code->text);
            $this->assertEquals('globalScoreContribution', $questionnaireResponse->score->globalScoreContribution->code->coding[0]->code);
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
            'payload.contentAttachment.data',
            ['loc' => ['body', 'payload', 'contentAttachment', 'data']],
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

    abstract protected function buildValidArguments(string $currentDir): array;

    abstract protected static function getSpecificMissingFields(): array;
}
