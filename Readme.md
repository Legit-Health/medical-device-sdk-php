# PHP SDK for Integrating with Legit.Health's Medical Device API

Official SDK for integrating with Legit.Health's Medical Device API 🩺🤖

## Before You Start

Ensure that you have a registered username and password for our API before making any requests.

## Instructions

To start sending requests to Legit.Health's Dermatology API, create an instance of the `LegitHealth\MedicalDevice\MedicalDeviceClient` class. There are several ways to instantiate this object, but the simplest is by using the `createWithBaseUri` method. This static method accepts a single argument: the API's base URL, which varies depending on the environment you're working in. For development purposes, use `https://medical-device-pre.legit.health`.

The `MedicalDeviceClient` class provides three main methods:

- `login`: Requires your username and password, returning an access token that must be included in subsequent requests.
  
- `diagnosisSupport`: Sends a diagnosis support request to the API. Use this method to analyze a set of images to determine the probability of detected pathologies, or to retrieve metrics such as malignancy or the need for referral.

- `severityAssessment`: Sends a severity assessment request to evaluate the severity of a known condition. For example, you can use this method to assess the severity of a psoriasis lesion and calculate the corresponding `APASI` score

## Login Request

Before invoking the diagnosis support or severity assessment methods, you need to obtain an access token. This is done by calling the `login` method of the `MedicalDeviceClient` class. This method expects a username and a password and returns an `AccessToken` object. This object contains the access token and its expiration time in minutes.

```php
$medicalDeviceClient = MedicalDeviceClient::createWithBaseUri('url');
$accessToken = $medicalDeviceClient->login('username', 'password');
$bearerToken = new BearerToken($accessToken->value);
```

## Diagnosis Support Request

The `diagnosisSupport` method of the `MedicalDeviceClient` class accepts three arguments:

- An object of the `DiagnosisSupportArguments` class, containing the images to analyze. **Important**: The maximum allowed length for the `medias` argument is 3.
- A bearer token obtained by invoking the `login` method.
- An object containing request options such as `timeout`.

```php
$fileToUpload1 = $this->currentDir . '/tests/resources/psoriasis_01.png';
$image1 = file_get_contents($fileToUpload1);
$fileToUpload2 = $this->currentDir . '/tests/resources/psoriasis_02.png';
$image2 = file_get_contents($fileToUpload2);
$fileToUpload3 = $this->currentDir . '/tests/resources/psoriasis_03.png';
$image3 = file_get_contents($fileToUpload3);
$diagnosisSupportArguments = new DiagnosisSupportArguments(
    // three images at most
    medias: [
        base64_encode($image1),
        base64_encode($image2),
        base64_encode($image3)
    ]
);
```

Once you've created a `DiagnosisSupportArguments` object, you can send the request as follows:

```php
$medicalDeviceClient = MedicalDeviceClient::createWithBaseUri('https://...');
$response = $medicalDeviceClient->diagnosisSupport(
    $diagnosisSupportArguments, 
    $bearerToken
);
```

The response object, which is an instance of `DiagnosisSupportResponse`, contains several properties with the information returned by the API about the analyzed images:

- `clinicalIndicators`: an object of the `ClinicalIndicators` class with the probabilities of different suspicions, such as `hasCondition` or `malignancy`.
- `performanceIndicators`: contains the sensitivity, specificity, and entropy values.
- `conclusions`: an array of `Conclusion` objects with the detected pathologies and their probabilities. The total probability is distributed among the detected pathologies.
- `imagingStudySeries`: an array of `ImagingStudySeriesInstance` objects with information related to each image, such as conclusions, performance indicators, and clinical indicators. It also contains a `media` object that includes the modality of the image and whether it passed our Dermatology Image Quality Assessment (DIQA).
- `analysisDuration`: the time spent by the AI model analyzing the image.
- `effectiveDateTime`: the date and time of the report.

## Severity Assessment Requests

The `severityAssessment` method of the `MedicalDeviceClient` class accepts three arguments:

- An object of the `SeverityAssessmentArguments` class, containing the image whose severity is to be assessed along with the related questionnaires.
- A bearer token obtained by invoking the `login` method.
- An object containing request options such as `timeout`.

### Example: Severity Assessment Request for Psoriasis

Here’s how to send a severity assessment request for a patient diagnosed with psoriasis.

First, create the objects representing the questionnaires used to track the evolution of psoriasis:

```php
use LegitHealth\MedicalDevice\Arguments\Params\ApasiLocalQuestionnaire;
use LegitHealth\Dapi\MediaAnalyzerArguments\Questionnaires\Questionnaires;

// ...

$apasiLocal = new ApasiLocalQuestionnaire(3);
$questionnaires = new Questionnaires([$apasiLocal]);
```

Then, create an object of the `SeverityAssessmentArguments` class:

```php
$fileToUpload = $this->currentDir . '/tests/resources/psoriasis_02.png';
$image = file_get_contents($fileToUpload);
$apasiLocal = new ApasiLocalQuestionnaire(3);
$questionnaires = new Questionnaires([$apasiLocal]);
$severityAssessmentArguments = new SeverityAssessmentArguments(
    base64_encode($image),
    scoringSystems: array_map(
        fn (Questionnaire $questionnaire) => $questionnaire->getName(), 
        $questionnaires->questionnaires
    ),
    questionnaires: $questionnaires,
    knownCondition: new KnownCondition('Psoriasis'),
    bodySiteCode: ParamsBodySiteCode::ArmLeft
);
```

Unlike diagnosis support requests, severity assessment requests support the following additional arguments:

- `scoringSystems`: an array of strings with the names of the scoring systems to be calculated. It supports all codes returned by the `Questionnaire` classes within the `LegitHealth\MedicalDevice\Arguments\Params` namespace.
  
- `questionnaires`: an object of the `Questionnaires` class with the answers required for practitioner or patient input. For psoriasis, the `surface` value is needed when creating an `ApasiLocalQuestionnaire` object.

Once the `SeverityAssessmentArguments` object is created, send the request as follows:

```php
$medicalDeviceClient = MedicalDeviceClient::createWithBaseUri('https://...');
$response = $medicalDeviceClient->severityAssessment(
    $severityAssessmentArguments, 
    $bearerToken
);
```

The response object contains several properties with information returned by the API about the analyzed image:

- `media`: contains the image modality and its validity, including whether it passed the Dermatology Image Quality Assessment (DIQA).
- `patientEvolution`: an array of `PatientEvolutionInstance` objects containing the score for each calculated scoring system along with its corresponding items.
- `analysisDuration`: the time spent by the AI model analyzing the image.

#### The `PatientEvolutionInstance` Class

The `PatientEvolutionInstance` contains all the information about a scoring system, for example, `APASI_LOCAL`.

You can access the value of a scoring system using the `getPatientEvolutionInstance` method:

```php
$apasiLocalScoringSystemValue = $response->getPatientEvolutionInstance(ScoringSystemCode::ApasiLocal);
```

This object contains the following properties:

- `scoringSystemCode`
- `score`: the calculated score for the scoring system.
- `items`: an array of `EvolutionItem` objects representing each item used to calculate the scoring system.
- `attachments`: an array of images that contain an overlay with detections made by the AI model.
- `detections`: an array of `Detection` objects representing each box identifying a lesion detected in the image.

Once you have a `PatientEvolutionInstance` object, you can access the value of each item using the `getEvolutionItem(string $itemCode)` method. Each item contains its code and the calculated value. For example, the `APASI_LOCAL` scoring system includes 4 items: desquamation, erythema, induration, and surface.

Full example:

```php
$apasiLocalScoringSystemValue = $response->getPatientEvolutionInstance(ScoringSystemCode::ApasiLocal);
// score
$apasiLocalScoringSystemValue->score->value
// score interpretation
$apasiLocalScoringSystemValue->score->interpretation
// accessing desquamation item
$apasiLocalScoringSystemValue->getEvolutionItem('desquamation');
$apasiLocalScoringSystemValue->getEvolutionItem('desquamation')->value;
// raw values output by the AI model
$apasiLocalScoringSystemValue->getEvolutionItem('desquamation')->additionalData;
```