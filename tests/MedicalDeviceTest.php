<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\{SeverityAssessmentArguments};
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
    Questionnaires,
    ScoradLocalQuestionnaire,
    ScoringSystemCode,
    SevenPCQuestionnaire,
    UasLocalQuestionnaire,
    UctQuestionnaire
};
use Dotenv\Dotenv;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\AccessToken;
use LegitHealth\MedicalDevice\MedicalDeviceClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MedicalDeviceTest extends TestCase
{
    private HttpClientInterface $httpClient;
    private AccessToken $accessToken;
    private string $currentDir;

    public function setUp(): void
    {
        $this->currentDir = getcwd();
        $dotenv = Dotenv::createImmutable($this->currentDir, '.env.local');
        $dotenv->load();
        $medicalDeviceClient = MedicalDeviceClient::createWithBaseUri($_ENV['API_URL']);
        $this->accessToken = $medicalDeviceClient->login($_ENV['API_USERNAME'], $_ENV['API_PASSWORD']);
        $this->httpClient = HttpClient::createForBaseUri($_ENV['API_URL']);
    }

    public function testMissingMediaInDiagnosisSupport()
    {
        $response = $this->httpClient->request('POST', '/diagnosis-support', [
            'json' => [
                'subject' => [
                    'reference' => 'xxx'
                ]
            ],
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->accessToken->value)
            ],
        ]);
        $this->assertEquals(422, $response->getStatusCode());
        $json = $response->toArray(false);
        $this->assertEquals('missing', $json['detail'][0]['type']);
        $this->assertEquals('media', $json['detail'][0]['loc'][1]);
        $this->assertEquals(
            "Field required",
            $json['detail'][0]['msg']
        );
    }

    public function testMissingMedia()
    {
        $response = $this->httpClient->request('POST', '/severity-assessment', [
            'json' => [
                'scoringSystems' => [
                    ScoringSystemCode::PasiLocal->value,
                    ScoringSystemCode::ApasiLocal->value,
                    ScoringSystemCode::Dlqi->value,
                    ScoringSystemCode::Pure4->value
                ],
                'questionnaireResponse' => [],
                'knownCondition' => [
                    'conclusion' => [
                        'display' => 'Psoriasis'
                    ]
                ],
                'bodySite' => 'arm_left'
            ],
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->accessToken->value)
            ],
        ]);
        $this->assertEquals(422, $response->getStatusCode());
        $json = $response->toArray(false);
        $this->assertEquals('missing', $json['detail'][0]['type']);
        $this->assertEquals('media', $json['detail'][0]['loc'][1]);
        $this->assertEquals(
            "Field required",
            $json['detail'][0]['msg']
        );
    }

    public function testMissingKnwonCondition()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/psoriasis_02.png';
        $image = file_get_contents($fileToUpload);

        $response = $this->httpClient->request('POST', '/severity-assessment', [
            'json' => [
                'media' => [
                    'data' => base64_encode($image)
                ],
                'scoringSystems' => [
                    ScoringSystemCode::PasiLocal->value,
                    ScoringSystemCode::ApasiLocal->value,
                    ScoringSystemCode::Dlqi->value,
                    ScoringSystemCode::Pure4->value
                ],
                'questionnaireResponse' => []
            ],
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->accessToken->value)
            ],
        ]);
        $this->assertEquals(422, $response->getStatusCode());
        $json = $response->toArray(false);
        $this->assertEquals('missing', $json['detail'][0]['type']);
        $this->assertEquals('knownCondition', $json['detail'][0]['loc'][1]);
        $this->assertEquals(
            "Field required",
            $json['detail'][0]['msg']
        );
    }

    public function testMissingQuestionnaire()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/psoriasis_02.png';
        $image = file_get_contents($fileToUpload);

        $response = $this->httpClient->request('POST', '/severity-assessment', [
            'json' => [
                'media' => [
                    'data' => base64_encode($image)
                ],
                'scoringSystems' => [
                    ScoringSystemCode::PasiLocal->value,
                    ScoringSystemCode::ApasiLocal->value,
                    ScoringSystemCode::Dlqi->value,
                    ScoringSystemCode::Pure4->value
                ],
                'questionnaireResponse' => [],
                'knownCondition' => [
                    'conclusion' => [
                        'display' => 'Psoriasis'
                    ]
                ],
                'bodySite' => 'arm_left'
            ],
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->accessToken->value)
            ],
        ]);
        $this->assertEquals(422, $response->getStatusCode());
        $json = $response->toArray(false);
        $this->assertEquals('value_error', $json['detail'][0]['type']);
        $this->assertArrayNotHasKey('input', $json['detail'][0]);
        $this->assertEquals(
            "Value error, Please, submit the questionnaire answers for the following scoring systems: ['pasiLocal', 'dlqi', 'pure4', 'apasiLocal']",
            $json['detail'][0]['msg']
        );
    }

    public function testEmptyScoringSystems()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/psoriasis_02.png';
        $image = file_get_contents($fileToUpload);

        $response = $this->httpClient->request('POST', '/severity-assessment', [
            'json' => [
                'media' => [
                    'data' => base64_encode($image)
                ],
                'scoringSystems' => [],
                'questionnaireResponse' => [
                    [
                        'questionnaire' => ScoringSystemCode::ApasiLocal->value,
                        'item' => [
                            [
                                'code' => 'surface',
                                'answer' => [
                                    [
                                        'value' => 7
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'knownCondition' => [
                    'conclusion' => [
                        'display' => 'Psoriasis'
                    ]
                ],
                'bodySite' => 'arm_left'
            ],
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->accessToken->value)
            ],
        ]);
        $this->assertEquals(422, $response->getStatusCode());
        $json = $response->toArray(false);
        $this->assertEquals('too_short', $json['detail'][0]['type']);
        $this->assertEquals('scoringSystems', $json['detail'][0]['loc'][1]);
        $this->assertEquals(
            "List should have at least 1 item after validation, not 0",
            $json['detail'][0]['msg']
        );
    }

    public function testUnespecifiedQuestionnaire()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/psoriasis_02.png';
        $image = file_get_contents($fileToUpload);

        $response = $this->httpClient->request('POST', '/severity-assessment', [
            'json' => [
                'media' => [
                    'data' => base64_encode($image)
                ],
                'scoringSystems' => [ScoringSystemCode::PasiLocal->value],
                'questionnaireResponse' => [
                    [
                        'questionnaire' => ScoringSystemCode::ApasiLocal->value,
                        'item' => [
                            [
                                'code' => 'surface',
                                'answer' => [
                                    [
                                        'value' => 7
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'questionnaire' => ScoringSystemCode::PasiLocal->value,
                        'item' => [
                            [
                                'code' => 'surface',
                                'answer' => [
                                    [
                                        'value' => 7
                                    ]
                                ]
                            ],
                            [
                                'code' => 'erythema',
                                'answer' => [
                                    [
                                        'value' => 1
                                    ]
                                ]
                            ],
                            [
                                'code' => 'induration',
                                'answer' => [
                                    [
                                        'value' => 2
                                    ]
                                ]
                            ],
                            [
                                'code' => 'desquamation',
                                'answer' => [
                                    [
                                        'value' => 3
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'knownCondition' => [
                    'conclusion' => [
                        'display' => 'Psoriasis'
                    ]
                ],
                'bodySite' => 'arm_left'
            ],
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->accessToken->value)
            ],
        ]);
        $this->assertEquals(422, $response->getStatusCode());
        $json = $response->toArray(false);
        $this->assertEquals('value_error', $json['detail'][0]['type']);
        $this->assertEquals(
            "Value error, You have sent questionnaires for scoring systems that you have not listed. The unrelated questionnaires are: ['apasiLocal']",
            $json['detail'][0]['msg']
        );
    }

    public function testOutOfRange()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/psoriasis_02.png';
        $image = file_get_contents($fileToUpload);

        $response = $this->httpClient->request('POST', '/severity-assessment', [
            'json' => [
                'media' => [
                    'data' => base64_encode($image)
                ],
                'scoringSystems' => [
                    ScoringSystemCode::ApasiLocal->value
                ],
                'questionnaireResponse' => [
                    [
                        'questionnaire' => ScoringSystemCode::ApasiLocal->value,
                        'item' => [
                            [
                                'code' => 'surface',
                                'answer' => [
                                    [
                                        'value' => 7
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'knownCondition' => [
                    'conclusion' => [
                        'display' => 'Psoriasis'
                    ]
                ],
                'bodySite' => 'arm_left'
            ],
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->accessToken->value)
            ],
        ]);
        $this->assertEquals(422, $response->getStatusCode());
        $json = $response->toArray(false);
        $this->assertEquals('less_than_equal', $json['detail'][0]['type']);
        $this->assertEquals('surface', $json['detail'][0]['loc'][1]);
        $this->assertEquals(
            "Input should be less than or equal to 6",
            $json['detail'][0]['msg']
        );
    }

    public function testWrongQuestionnaireKey()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/psoriasis_02.png';
        $image = file_get_contents($fileToUpload);

        $response = $this->httpClient->request('POST', '/severity-assessment', [
            'json' => [
                'media' => [
                    'data' => base64_encode($image)
                ],
                'scoringSystems' => [
                    ScoringSystemCode::ApasiLocal->value
                ],
                'questionnaireResponse' => [
                    [
                        'questionnaire' => ScoringSystemCode::ApasiLocal->value,
                        'item' => [
                            [
                                'code' => 'surfaces',
                                'answer' => [
                                    [
                                        'value' => 7
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'knownCondition' => [
                    'conclusion' => [
                        'display' => 'Psoriasis'
                    ]
                ],
                'bodySite' => 'arm_left'
            ],
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->accessToken->value)
            ],
        ]);
        $this->assertEquals(422, $response->getStatusCode());
        $json = $response->toArray(false);
        $this->assertEquals('missing', $json['detail'][0]['type']);
        $this->assertEquals('surface', $json['detail'][0]['loc'][1]);
        $this->assertEquals(
            "Field required",
            $json['detail'][0]['msg']
        );

        $this->assertEquals('extra_forbidden', $json['detail'][1]['type']);
        $this->assertEquals('surfaces', $json['detail'][1]['loc'][1]);
        $this->assertEquals(
            "Extra inputs are not permitted",
            $json['detail'][1]['msg']
        );
    }
}
