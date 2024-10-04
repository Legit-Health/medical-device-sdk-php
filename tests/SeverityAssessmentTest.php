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
    Questionnaires,
    ResvechLocalQuestionnaire,
    ScoradLocalQuestionnaire,
    ScoringSystemCode,
    SevenPCQuestionnaire,
    UasLocalQuestionnaire,
    UctQuestionnaire
};
use Dotenv\Dotenv;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\DetectionCode;
use LegitHealth\MedicalDevice\MedicalDeviceClient;
use PHPUnit\Framework\TestCase;

class SeverityAssessmentTest extends TestCase
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

    public function testPsoriasis()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/psoriasis_02.png';
        $image = file_get_contents($fileToUpload);
        $apasiLocal = new ApasiLocalQuestionnaire(3);
        $pasiLocal = new PasiLocalQuestionnaire(3, 2, 1, 1);
        $pure4 = new Pure4Questionnaire(0, 0, 0, 1);
        $dlqi = new DlqiQuestionnaire(1, 1, 2, 0, 0, 0, 1, 2, 2, 0);
        $pga = new PgaQuestionnaire(3, 2, 4);
        $questionnaires = new Questionnaires([$apasiLocal, $pasiLocal, $dlqi, $pure4, $pga]);
        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystems: array_map(fn (Questionnaire $questionnaire) => $questionnaire->getName(), $questionnaires->questionnaires),
            questionnaires: $questionnaires,
            knownCondition: new KnownCondition('Psoriasis'),
            bodySiteCode: ParamsBodySiteCode::ArmLeft
        );
        $response = $this->medicalDeviceClient->severityAssessment($severityAssessmentArguments, $this->bearerToken);

        $this->assertNotEmpty($response->modality);
        $mediaValidity = $response->mediaValidity;
        $this->assertTrue($mediaValidity->isValid);
        $this->assertGreaterThan(0, $mediaValidity->getDiqaScore());
        foreach ($mediaValidity->metrics as $validityMetric) {
            $this->assertTrue($validityMetric->isValid);
            $this->assertNotEmpty($validityMetric->name);
        }
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertCount(5, $response->scoringSystemsResults);

        // APASI
        $apasiLocalScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::ApasiLocal);
        $this->assertGreaterThanOrEqual(0, $apasiLocalScoringSystemValue->score->value);
        $this->assertNotNull($apasiLocalScoringSystemValue->score->interpretation);


        $this->assertCount(1, $apasiLocalScoringSystemValue->getFacetScore('desquamation')->additionalData);
        $this->assertThat(
            $apasiLocalScoringSystemValue->getFacetScore('desquamation')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );


        $this->assertCount(1, $apasiLocalScoringSystemValue->getFacetScore('erythema')->additionalData);
        $this->assertThat(
            $apasiLocalScoringSystemValue->getFacetScore('erythema')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );


        $this->assertCount(1, $apasiLocalScoringSystemValue->getFacetScore('induration')->additionalData);
        $this->assertThat(
            $apasiLocalScoringSystemValue->getFacetScore('induration')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );


        $this->assertCount(0, $apasiLocalScoringSystemValue->getFacetScore('surface')->additionalData);
        $this->assertThat(
            $apasiLocalScoringSystemValue->getFacetScore('surface')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(6)
            )
        );
        $this->assertCount(1, $apasiLocalScoringSystemValue->attachments);

        // DLQI
        $dlqiScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::Dlqi);
        $this->assertGreaterThanOrEqual(0, $dlqiScoringSystemValue->score->value);
        $this->assertNotEmpty($dlqiScoringSystemValue->score->interpretation);
        foreach (range(1, 10) as $number) {
            $facetCode = sprintf('question%d', $number);
            $facetScore = $dlqiScoringSystemValue->getFacetScore($facetCode);
            $this->assertCount(0, $facetScore->additionalData);
            $this->assertThat(
                $facetScore->value,
                $this->logicalAnd(
                    $this->greaterThanOrEqual(0),
                    $this->lessThanOrEqual(3)
                )
            );
        }
        $this->assertCount(0, $dlqiScoringSystemValue->attachments);

        // PASI_LOCAL
        $pasiLocalScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::PasiLocal);
        $this->assertGreaterThanOrEqual(0, $pasiLocalScoringSystemValue->score->value);
        $this->assertNotEmpty($pasiLocalScoringSystemValue->score->interpretation);

        $this->assertCount(0, $pasiLocalScoringSystemValue->getFacetScore('desquamation')->additionalData);
        $this->assertThat(
            $pasiLocalScoringSystemValue->getFacetScore('desquamation')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );

        $this->assertCount(0, $pasiLocalScoringSystemValue->getFacetScore('erythema')->additionalData);
        $this->assertThat(
            $pasiLocalScoringSystemValue->getFacetScore('erythema')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );

        $this->assertCount(0, $pasiLocalScoringSystemValue->getFacetScore('induration')->additionalData);
        $this->assertThat(
            $pasiLocalScoringSystemValue->getFacetScore('induration')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );

        $this->assertCount(0, $pasiLocalScoringSystemValue->getFacetScore('surface')->additionalData);
        $this->assertThat(
            $pasiLocalScoringSystemValue->getFacetScore('surface')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(6)
            )
        );
        $this->assertCount(0, $pasiLocalScoringSystemValue->attachments);

        // PURE4
        $pure4ScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::Pure4);
        $this->assertGreaterThanOrEqual(0, $pure4ScoringSystemValue->score->value);
        $this->assertNotEmpty($pure4ScoringSystemValue->score->interpretation);
        foreach (range(1, 4) as $number) {
            $facetCode = sprintf('question%d', $number);
            $facetScore = $pure4ScoringSystemValue->getFacetScore($facetCode);
            $this->assertCount(0, $facetScore->additionalData);
            $this->assertThat(
                $facetScore->value,
                $this->logicalAnd(
                    $this->greaterThanOrEqual(0),
                    $this->lessThanOrEqual(3)
                )
            );
        }
        $this->assertCount(0, $pure4ScoringSystemValue->attachments);

        // PGA
        $pgaScoringSystmeValue = $response->getScoringSystemResult(ScoringSystemCode::Pga);
        $this->assertEquals(3, $pgaScoringSystmeValue->score->value);
        $this->assertNotEmpty($pgaScoringSystmeValue->score->interpretation);

        foreach (['erythema', 'desquamation', 'induration'] as $facetCode) {
            $facetScore = $pgaScoringSystmeValue->getFacetScore($facetCode);
            $this->assertThat(
                $facetScore->value,
                $this->logicalAnd(
                    $this->greaterThanOrEqual(0)
                )
            );
            $this->assertCount(0, $facetScore->additionalData);
        }
        $this->assertCount(0, $pgaScoringSystmeValue->attachments);
        $this->assertGreaterThan(0, $pgaScoringSystmeValue->detections);
    }

    public function testAcne()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/acne.jpg';
        $image = file_get_contents($fileToUpload);
        $gagsQuestionnaire = new GagsQuestionnaire(0, 1, 2, 3, 0, 1);
        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystems: [ScoringSystemCode::AladinLocal->value, $gagsQuestionnaire::getName()],
            knownCondition: new KnownCondition('Acne'),
            bodySiteCode: ParamsBodySiteCode::HeadFront,
            questionnaires: new Questionnaires([$gagsQuestionnaire])
        );
        $response = $this->medicalDeviceClient->severityAssessment($severityAssessmentArguments, $this->bearerToken);

        $this->assertNotEmpty($response->modality);
        $mediaValidity = $response->mediaValidity;
        $this->assertTrue($mediaValidity->isValid);
        $this->assertGreaterThan(0, $mediaValidity->getDiqaScore());
        foreach ($mediaValidity->metrics as $validityMetric) {
            $this->assertTrue($validityMetric->isValid);
            $this->assertNotEmpty($validityMetric->name);
        }
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertCount(2, $response->scoringSystemsResults);

        // ALADIN
        $aladinScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::AladinLocal);
        $this->assertGreaterThanOrEqual(0, $aladinScoringSystemValue->score->value);
        $this->assertNotNull($aladinScoringSystemValue->score->interpretation);

        $this->assertCount(
            1,
            $aladinScoringSystemValue->getFacetScore('acneLesion')->additionalData
        );
        $this->assertThat(
            $aladinScoringSystemValue->getFacetScore('acneLesion')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(100)
            )
        );

        $this->assertCount(1, $aladinScoringSystemValue->attachments);
        $this->assertGreaterThanOrEqual(0, $aladinScoringSystemValue->detections);
        $detection = $aladinScoringSystemValue->detections[0];
        $this->assertGreaterThanOrEqual(0, $detection->confidence);
        $this->assertGreaterThanOrEqual(0, $detection->box->p1->x);
        $this->assertGreaterThanOrEqual(0, $detection->box->p1->y);
        $this->assertGreaterThanOrEqual(0, $detection->box->p2->x);
        $this->assertGreaterThanOrEqual(0, $detection->box->p2->y);
        $this->assertEquals(DetectionCode::AcneLesion, $detection->code);

        // GAGS
        $gagsScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::Gags);
        $this->assertGreaterThanOrEqual(0, $gagsScoringSystemValue->score->value);
        $this->assertNotEmpty($gagsScoringSystemValue->score->interpretation);

        foreach (['forehead', 'rightCheek', 'leftCheek', 'nose', 'chin', 'chestAndUpperBack'] as $facetCode) {
            $facetScore = $gagsScoringSystemValue->getFacetScore($facetCode);
            $this->assertThat(
                $facetScore->value,
                $this->logicalAnd(
                    $this->greaterThanOrEqual(0)
                )
            );
            $this->assertCount(0, $facetScore->additionalData);
        }
        $this->assertCount(0, $gagsScoringSystemValue->attachments);
        $this->assertGreaterThan(0, $gagsScoringSystemValue->detections);
    }

    // public function testAcneFaceAnteriorProjection()
    // {
    //     $currentDir = getcwd();
    //     $dotenv = Dotenv::createImmutable($currentDir, '.env.local');
    //     $dotenv->load();
    //     $mediaAnalyzer = MediaAnalyzer::createWithParams(
    //         $_ENV['API_URL'],
    //         $_ENV['API_KEY']
    //     );

    //     $currentDir = getcwd();
    //     $fileToUpload = $currentDir . '/tests/resources/acne_face.jpg';
    //     $image = file_get_contents($fileToUpload);

    //     $severityAssessmentData = new SeverityAssessmentData(
    //         content: base64_encode($image),
    //         pathologyCode: 'Acne',
    //         bodySiteCode: BodySiteCode::HeadFront,
    //         previousMedias: [],
    //         operator: Operator::Patient,
    //         subject: new Subject(
    //             $this->generateRandom(),
    //             Gender::Male,
    //             '1.75',
    //             '70',
    //             DateTimeImmutable::createFromFormat('Ymd', '19861020'),
    //             $this->generateRandom(),
    //             new Company($this->generateRandom(), 'Company')
    //         ),
    //         scoringSystems: ['ALEGI'],
    //         questionnaires: new Questionnaires([]),
    //         view: View::AnteriorProjection
    //     );

    //     $MedicalDeviceArguments = new SeverityAssessmentArguments(
    //         $this->generateRandom(),
    //         $severityAssessmentData,
    //         new OrderDetail(faceDetection: true)
    //     );

    //     $response = $mediaAnalyzer->severityAssessment($MedicalDeviceArguments);

    //     $preliminaryFindings = $response->preliminaryFindings;
    //     $this->assertGreaterThanOrEqual(0, $preliminaryFindings->hasConditionSuspicion);
    //     $this->assertGreaterThanOrEqual(0, $preliminaryFindings->isPreMalignantSuspicion);
    //     $this->assertGreaterThanOrEqual(0, $preliminaryFindings->isMalignantSuspicion);
    //     $this->assertGreaterThanOrEqual(0, $preliminaryFindings->needsBiopsySuspicion);
    //     $this->assertGreaterThanOrEqual(0, $preliminaryFindings->needsSpecialistsAttention);

    //     $this->assertNotEmpty($response->modality);

    //     $mediaValidity = $response->mediaValidity;
    //     $this->assertTrue($mediaValidity->isValid);
    //     $this->assertGreaterThan(0, $mediaValidity->diqaScore);
    //     foreach ($mediaValidity->validityMetrics as $validityMetric) {
    //         $this->assertTrue($validityMetric->pass);
    //         $this->assertNotEmpty($validityMetric->name);
    //     }

    //     $metrics = $response->metrics;
    //     $this->assertGreaterThan(0, $metrics->sensitivity);
    //     $this->assertGreaterThan(0, $metrics->specificity);

    //     $this->assertGreaterThan(0, $response->iaSeconds);

    //     $explainabilityMedia = $response->explainabilityMedia;
    //     $this->assertNotNull($response->explainabilityMedia);
    //     $this->assertNull($explainabilityMedia->content);
    //     $this->assertNotNull($explainabilityMedia->metrics->pxToCm);

    //     $this->assertCount(1, $response->scoringSystemsResults);

    //     // ALEGI
    //     $alegiScoringSystemValue = $response->getScoringSystemResult('ALEGI');
    //     $this->assertGreaterThanOrEqual(0, $alegiScoringSystemValue->getScore()->score);
    //     $this->assertNotNull($alegiScoringSystemValue->getScore()->category);

    //     $this->assertNotNull($alegiScoringSystemValue->getFacetScore('lesionDensity')->intensity);
    //     $this->assertThat(
    //         $alegiScoringSystemValue->getFacetScore('lesionDensity')->intensity,
    //         $this->logicalAnd(
    //             $this->greaterThanOrEqual(0),
    //             $this->lessThanOrEqual(100)
    //         )
    //     );
    //     $this->assertThat(
    //         $alegiScoringSystemValue->getFacetScore('lesionDensity')->value,
    //         $this->logicalAnd(
    //             $this->greaterThanOrEqual(0),
    //             $this->lessThanOrEqual(4)
    //         )
    //     );

    //     $this->assertNotNull($alegiScoringSystemValue->getFacetScore('lesionNumber')->intensity);
    //     $this->assertThat(
    //         $alegiScoringSystemValue->getFacetScore('lesionNumber')->intensity,
    //         $this->logicalAnd(
    //             $this->greaterThanOrEqual(0),
    //             $this->lessThanOrEqual(100)
    //         )
    //     );
    //     $this->assertGreaterThan(0, $alegiScoringSystemValue->getFacetScore('lesionNumber')->value);
    //     $this->assertNotNull(
    //         $alegiScoringSystemValue->explainabilityMedia->content
    //     );
    //     $this->assertGreaterThanOrEqual(0, $alegiScoringSystemValue->explainabilityMedia->detections);
    //     if (count($alegiScoringSystemValue->explainabilityMedia->detections) > 0) {
    //         $detection = $alegiScoringSystemValue->explainabilityMedia->detections[0];
    //         $this->assertGreaterThanOrEqual(0, $detection->confidence);
    //         $this->assertGreaterThanOrEqual(0, $detection->p1->x);
    //         $this->assertGreaterThanOrEqual(0, $detection->p1->y);
    //         $this->assertGreaterThanOrEqual(0, $detection->p2->x);
    //         $this->assertGreaterThanOrEqual(0, $detection->p2->y);
    //         $this->assertEquals(DetectionLabel::AcneLesion, $detection->detectionLabel);
    //     }
    // }

    public function testUrticariaSeverityAssessment()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/urticaria.jpg';
        $image = file_get_contents($fileToUpload);

        $auasLocal = new AuasLocalQuestionnaire(2);
        $uasLocal = new UasLocalQuestionnaire(2, 3);
        $uct = new UctQuestionnaire(0, 2, 2, 4);
        $dlqi = new DlqiQuestionnaire(1, 1, 2, 0, 0, 0, 1, 2, 2, 0);
        $questionnaires = new Questionnaires([$auasLocal, $uasLocal, $dlqi, $uct]);

        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystems: array_map(fn (Questionnaire $questionnaire) => $questionnaire->getName(), $questionnaires->questionnaires),
            questionnaires: $questionnaires,
            knownCondition: new KnownCondition('Urticaria'),
            bodySiteCode: ParamsBodySiteCode::HeadFront
        );
        $response = $this->medicalDeviceClient->severityAssessment($severityAssessmentArguments, $this->bearerToken);

        $this->assertNotEmpty($response->modality);
        $mediaValidity = $response->mediaValidity;
        $this->assertTrue($mediaValidity->isValid);
        $this->assertGreaterThan(0, $mediaValidity->getDiqaScore());
        foreach ($mediaValidity->metrics as $validityMetric) {
            $this->assertTrue($validityMetric->isValid);
            $this->assertNotEmpty($validityMetric->name);
        }
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertCount(4, $response->scoringSystemsResults);

        // AUAS_LOCAL
        $auasLocalScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::AuasLocal);
        $this->assertGreaterThanOrEqual(0, $auasLocalScoringSystemValue->score->value);
        $this->assertNotNull($auasLocalScoringSystemValue->score->interpretation);

        $this->assertNotNull($auasLocalScoringSystemValue->getFacetScore('hive')->value);
        $this->assertNotNull($auasLocalScoringSystemValue->getFacetScore('hive')->code);
        $this->assertCount(1, $auasLocalScoringSystemValue->getFacetScore('hive')->additionalData);
        $this->assertGreaterThanOrEqual(0, $auasLocalScoringSystemValue->getFacetScore('hive')->value);


        $this->assertCount(0, $auasLocalScoringSystemValue->getFacetScore('itchiness')->additionalData);
        $this->assertThat(
            $auasLocalScoringSystemValue->getFacetScore('itchiness')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertGreaterThan(0, \count($auasLocalScoringSystemValue->attachments));
        $this->assertGreaterThan(0, \count($auasLocalScoringSystemValue->detections));
        $detection = $auasLocalScoringSystemValue->detections[0];
        $this->assertGreaterThanOrEqual(0, $detection->confidence);
        $this->assertGreaterThanOrEqual(0, $detection->box->p1->x);
        $this->assertGreaterThanOrEqual(0, $detection->box->p1->y);
        $this->assertGreaterThanOrEqual(0, $detection->box->p2->x);
        $this->assertGreaterThanOrEqual(0, $detection->box->p2->y);
        $this->assertEquals(DetectionCode::Hive, $detection->code);


        // DLQI
        $dlqiScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::Dlqi);
        $this->assertGreaterThanOrEqual(0, $dlqiScoringSystemValue->score->value);
        $this->assertNotEmpty($dlqiScoringSystemValue->score->interpretation);
        foreach (range(1, 10) as $number) {
            $facetCode = sprintf('question%d', $number);
            $facetScore = $dlqiScoringSystemValue->getFacetScore($facetCode);
            $this->assertCount(0, $facetScore->additionalData);
            $this->assertThat(
                $facetScore->value,
                $this->logicalAnd(
                    $this->greaterThanOrEqual(0),
                    $this->lessThanOrEqual(3)
                )
            );
        }
        $this->count(0, $dlqiScoringSystemValue->attachments);
        $this->count(0, $dlqiScoringSystemValue->detections);


        // UAS_LOCAL
        $uasLocalScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::UasLocal);
        $this->assertGreaterThanOrEqual(0, $dlqiScoringSystemValue->score->value);
        $this->assertNotEmpty($dlqiScoringSystemValue->score->interpretation);

        $this->assertCount(0, $uasLocalScoringSystemValue->getFacetScore('hive')->additionalData);
        $this->assertThat(
            $uasLocalScoringSystemValue->getFacetScore('hive')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0)
            )
        );

        $this->assertCount(0, $uasLocalScoringSystemValue->getFacetScore('hive')->additionalData);
        $this->assertThat(
            $uasLocalScoringSystemValue->getFacetScore('itchiness')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(4)
            )
        );

        $this->count(0, $dlqiScoringSystemValue->attachments);
        $this->count(0, $dlqiScoringSystemValue->detections);

        // UCT
        $uctScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::Uct);
        $this->assertEquals(8, $uctScoringSystemValue->score->value);
        $this->assertNotEmpty($uctScoringSystemValue->score->interpretation);

        foreach (['physicalSymptoms', 'qualityOfLife', 'treatmentNotEnough', 'overallUnderControl'] as $facetCode) {
            $facetScore = $uctScoringSystemValue->getFacetScore($facetCode);
            $this->assertThat(
                $facetScore->value,
                $this->logicalAnd(
                    $this->greaterThanOrEqual(0)
                )
            );
            $this->assertCount(0, $facetScore->additionalData);
        }
        $this->assertCount(0, $uctScoringSystemValue->attachments);
        $this->assertGreaterThan(0, $uctScoringSystemValue->detections);
    }

    public function testAtopicDermatitis()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/dermatitis.jpg';
        $image = file_get_contents($fileToUpload);
        $ascoradLocal = new AscoradLocalQuestionnaire(27, 2, 3);
        $scoradLocal = new ScoradLocalQuestionnaire(30, 1, 2, 3, 1, 2, 3, 1, 2);
        $questionnaires = new Questionnaires([$scoradLocal, $ascoradLocal]);
        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystems: array_map(fn (Questionnaire $questionnaire) => $questionnaire->getName(), $questionnaires->questionnaires),
            questionnaires: $questionnaires,
            knownCondition: new KnownCondition('Atopic dermatitis'),
            bodySiteCode: ParamsBodySiteCode::ArmLeft
        );
        $response = $this->medicalDeviceClient->severityAssessment($severityAssessmentArguments, $this->bearerToken);


        $this->assertNotEmpty($response->modality);
        $mediaValidity = $response->mediaValidity;
        $this->assertTrue($mediaValidity->isValid);
        $this->assertGreaterThan(0, $mediaValidity->getDiqaScore());
        foreach ($mediaValidity->metrics as $validityMetric) {
            $this->assertTrue($validityMetric->isValid);
            $this->assertNotEmpty($validityMetric->name);
        }
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertCount(2, $response->scoringSystemsResults);

        // ASCORAD_LOCAL
        $ascoradLocalScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::AscoradLocal);
        $this->assertGreaterThanOrEqual(0, $ascoradLocalScoringSystemValue->score->value);
        $this->assertNotNull($ascoradLocalScoringSystemValue->score->interpretation);

        $this->assertCount(1, $ascoradLocalScoringSystemValue->getFacetScore('crusting')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('crusting')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertCount(1, $ascoradLocalScoringSystemValue->getFacetScore('dryness')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('dryness')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertCount(1, $ascoradLocalScoringSystemValue->getFacetScore('erythema')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('erythema')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertCount(1, $ascoradLocalScoringSystemValue->getFacetScore('excoriation')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('excoriation')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertCount(1, $ascoradLocalScoringSystemValue->getFacetScore('lichenification')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('lichenification')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertCount(1, $ascoradLocalScoringSystemValue->getFacetScore('swelling')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('swelling')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertCount(0, $ascoradLocalScoringSystemValue->getFacetScore('itchiness')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('itchiness')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(10)
            )
        );

        $this->assertCount(0, $ascoradLocalScoringSystemValue->getFacetScore('sleeplessness')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('sleeplessness')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(10)
            )
        );

        $this->assertCount(0, $ascoradLocalScoringSystemValue->getFacetScore('surface')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('surface')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(100)
            )
        );

        $this->assertCount(1, $ascoradLocalScoringSystemValue->attachments);
        $this->assertCount(0, $ascoradLocalScoringSystemValue->detections);


        // SCORAD_LOCAL
        $scoradLocalScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::ScoradLocal);
        $this->assertGreaterThanOrEqual(0, $scoradLocalScoringSystemValue->score->value);
        $this->assertNotNull($scoradLocalScoringSystemValue->score->interpretation);

        $this->assertCount(0, $scoradLocalScoringSystemValue->getFacetScore('crusting')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('crusting')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertCount(0, $scoradLocalScoringSystemValue->getFacetScore('dryness')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('dryness')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertCount(0, $scoradLocalScoringSystemValue->getFacetScore('erythema')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('erythema')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertCount(0, $scoradLocalScoringSystemValue->getFacetScore('excoriation')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('excoriation')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertCount(0, $scoradLocalScoringSystemValue->getFacetScore('lichenification')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('lichenification')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertCount(0, $scoradLocalScoringSystemValue->getFacetScore('swelling')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('swelling')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(3)
            )
        );

        $this->assertCount(0, $scoradLocalScoringSystemValue->getFacetScore('itchiness')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('itchiness')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(10)
            )
        );

        $this->assertCount(0, $scoradLocalScoringSystemValue->getFacetScore('sleeplessness')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('sleeplessness')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(10)
            )
        );

        $this->assertCount(0, $scoradLocalScoringSystemValue->getFacetScore('surface')->additionalData);
        $this->assertThat(
            $ascoradLocalScoringSystemValue->getFacetScore('surface')->value,
            $this->logicalAnd(
                $this->greaterThanOrEqual(0),
                $this->lessThanOrEqual(100)
            )
        );

        $this->assertCount(0, $scoradLocalScoringSystemValue->attachments);
        $this->assertCount(0, $scoradLocalScoringSystemValue->detections);
    }

    public function testHidradenitis()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/hidradenitis_01.png';
        $image = file_get_contents($fileToUpload);
        $ihs4Local = new Ihs4LocalQuestionnaire(1, 2, 1);
        $questionnaires = new Questionnaires([$ihs4Local]);
        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystems: [ScoringSystemCode::Ihs4Local->value, ScoringSystemCode::Aihs4Local->value],
            questionnaires: $questionnaires,
            knownCondition: new KnownCondition('Hidradenitis suppurativa'),
            bodySiteCode: ParamsBodySiteCode::ArmLeft
        );
        $response = $this->medicalDeviceClient->severityAssessment($severityAssessmentArguments, $this->bearerToken);


        $this->assertNotEmpty($response->modality);
        $mediaValidity = $response->mediaValidity;
        $this->assertTrue($mediaValidity->isValid);
        $this->assertGreaterThan(0, $mediaValidity->getDiqaScore());
        foreach ($mediaValidity->metrics as $validityMetric) {
            $this->assertTrue($validityMetric->isValid);
            $this->assertNotEmpty($validityMetric->name);
        }
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertCount(2, $response->scoringSystemsResults);

        // AIHS4_LOCAL
        $aihs4LocalScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::Aihs4Local);
        $this->assertGreaterThanOrEqual(0, $aihs4LocalScoringSystemValue->score->value);
        $this->assertNotNull($aihs4LocalScoringSystemValue->score->interpretation);

        $this->assertGreaterThanOrEqual(0, $aihs4LocalScoringSystemValue->getFacetScore('abscess')->value);
        $this->assertCount(0, $aihs4LocalScoringSystemValue->getFacetScore('abscess')->additionalData);
        $this->assertGreaterThanOrEqual(0, $aihs4LocalScoringSystemValue->getFacetScore('drainingTunnel')->value);
        $this->assertCount(0, $aihs4LocalScoringSystemValue->getFacetScore('drainingTunnel')->additionalData);
        $this->assertGreaterThanOrEqual(0, $aihs4LocalScoringSystemValue->getFacetScore('nodule')->value);
        $this->assertCount(0, $aihs4LocalScoringSystemValue->getFacetScore('nodule')->additionalData);

        $this->assertCount(1, $aihs4LocalScoringSystemValue->attachments);
        $this->assertGreaterThan(0, $aihs4LocalScoringSystemValue->detections);
        $detection = $aihs4LocalScoringSystemValue->detections[0];
        $this->assertGreaterThanOrEqual(0, $detection->confidence);
        $this->assertGreaterThanOrEqual(0, $detection->box->p1->x);
        $this->assertGreaterThanOrEqual(0, $detection->box->p1->y);
        $this->assertGreaterThanOrEqual(0, $detection->box->p2->x);
        $this->assertGreaterThanOrEqual(0, $detection->box->p2->y);
        $this->assertContains($detection->code, [DetectionCode::Nodule, DetectionCode::DrainingTunnel, DetectionCode::Abscess]);

        // IHS4_LOCAL
        $ihs4LocalScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::Ihs4Local);
        $this->assertGreaterThanOrEqual(0, $ihs4LocalScoringSystemValue->score->value);
        $this->assertNotEmpty($ihs4LocalScoringSystemValue->score->interpretation);

        foreach (['abscess', 'drainingTunnel', 'nodule'] as $facetCode) {
            $facetScore = $ihs4LocalScoringSystemValue->getFacetScore($facetCode);
            $this->assertThat(
                $facetScore->value,
                $this->logicalAnd(
                    $this->greaterThanOrEqual(0)
                )
            );
            $this->assertCount(0, $facetScore->additionalData);
        }
        $this->assertCount(0, $ihs4LocalScoringSystemValue->attachments);
        $this->assertGreaterThan(0, $ihs4LocalScoringSystemValue->detections);
    }

    public function testNevus()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/nevus.jpg';
        $image = file_get_contents($fileToUpload);
        $sevenPC = new SevenPCQuestionnaire(1, 1, 1, 0, 0, 0, 1);
        $questionnaires = new Questionnaires([$sevenPC]);
        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystems: [ScoringSystemCode::SevenPc],
            questionnaires: $questionnaires,
            knownCondition: new KnownCondition('Atypical nevus'),
            bodySiteCode: ParamsBodySiteCode::ArmLeft
        );
        $response = $this->medicalDeviceClient->severityAssessment($severityAssessmentArguments, $this->bearerToken);


        $this->assertNotEmpty($response->modality);
        $mediaValidity = $response->mediaValidity;
        $this->assertTrue($mediaValidity->isValid);
        $this->assertGreaterThan(0, $mediaValidity->getDiqaScore());
        foreach ($mediaValidity->metrics as $validityMetric) {
            $this->assertTrue($validityMetric->isValid);
            $this->assertNotEmpty($validityMetric->name);
        }
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertCount(1, $response->scoringSystemsResults);

        // NEVUS
        $sevenPcScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::SevenPc);
        $this->assertGreaterThanOrEqual(0, $sevenPcScoringSystemValue->score->value);
        $this->assertNotNull($sevenPcScoringSystemValue->score->interpretation);

        foreach (['irregularSize', 'irregularPigmentation', 'irregularBorder', 'inflammation', 'largerThanOtherLesions', 'itchOrAltered', 'crustedOrBleeding'] as $facetCode) {
            $facetScore = $sevenPcScoringSystemValue->getFacetScore($facetCode);
            $this->assertThat(
                $facetScore->value,
                $this->logicalAnd(
                    $this->greaterThanOrEqual(0)
                )
            );
            $this->assertCount(0, $facetScore->additionalData);
        }
        $this->assertCount(0, $sevenPcScoringSystemValue->attachments);
        $this->assertGreaterThan(0, $sevenPcScoringSystemValue->detections);
    }


    public function testUlcera()
    {
        $fileToUpload = $this->currentDir . '/tests/resources/ulcera_01.jpg';
        $image = file_get_contents($fileToUpload);
        $resvechQuestionnaire = new ResvechLocalQuestionnaire(0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0);
        $questionnaires = new Questionnaires([$resvechQuestionnaire]);
        $severityAssessmentArguments = new SeverityAssessmentArguments(
            base64_encode($image),
            scoringSystems: [ScoringSystemCode::ResvechLocal, ScoringSystemCode::ApulsiLocal],
            questionnaires: $questionnaires,
            knownCondition: new KnownCondition('Ulcera'),
            bodySiteCode: ParamsBodySiteCode::LegLeft
        );
        $response = $this->medicalDeviceClient->severityAssessment($severityAssessmentArguments, $this->bearerToken);


        $this->assertNotEmpty($response->modality);
        $mediaValidity = $response->mediaValidity;
        $this->assertTrue($mediaValidity->isValid);
        $this->assertGreaterThan(0, $mediaValidity->getDiqaScore());
        foreach ($mediaValidity->metrics as $validityMetric) {
            $this->assertTrue($validityMetric->isValid);
            $this->assertNotEmpty($validityMetric->name);
        }
        $this->assertGreaterThan(0, $response->analysisDuration);
        $this->assertCount(2, $response->scoringSystemsResults);

        // Apulsi
        $apulsiScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::ApulsiLocal);
        $this->assertGreaterThanOrEqual(0, $apulsiScoringSystemValue->score->value);
        $this->assertNull($apulsiScoringSystemValue->score->interpretation);
        $this->assertCount(23, $apulsiScoringSystemValue->facetScores);

        // Resvech
        $resvechScoringSystemValue = $response->getScoringSystemResult(ScoringSystemCode::ResvechLocal);
        $this->assertGreaterThanOrEqual(0, $resvechScoringSystemValue->score->value);
        $this->assertNotNull($resvechScoringSystemValue->score->interpretation);

        foreach (
            [
                'woundDimensions',
                'tissues',
                'edges',
                'tissueInWoundBed',
                'exudate',
                'frequencyOfPain',
                'macerationAroundWound',
                'tunneling',
                'increasingPain',
                'erythemaAroundWound',
                'edemaAroundWound',
                'temperatureRise',
                'increasingExudate',
                'purulentExudate',
                'tissueFriableOrBleedsEasily',
                'stationaryWound',
                'biofilmCompatibleTissue',
                'odor',
                'hypergranulation',
                'increasingWound',
                'satelliteLesions',
                'tissuePaleness'
            ] as $facetCode
        ) {
            $facetScore = $resvechScoringSystemValue->getFacetScore($facetCode);
            $this->assertThat(
                $facetScore->value,
                $this->logicalAnd(
                    $this->greaterThanOrEqual(0)
                )
            );
            $this->assertCount(0, $facetScore->additionalData);
        }
        $this->assertCount(0, $resvechScoringSystemValue->attachments);
        $this->assertGreaterThan(0, $resvechScoringSystemValue->detections);
    }
}
