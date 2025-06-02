<?php

namespace LegitHealth\MedicalDevice;

use LegitHealth\MedicalDevice\Common\BearerToken;
use LegitHealth\MedicalDevice\Arguments\{
    DiagnosisSupportArguments,
    MedicalDeviceArguments,
    RequestOptions,
    SeverityAssessmentAutomaticLocalArguments,
    SeverityAssessmentManualArguments
};
use LegitHealth\MedicalDevice\Response\{AccessToken, DiagnosisSupportResponse, SeverityAssessmentResponse};
use Throwable;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MedicalDeviceClient
{
    private const SEVERITY_ASSESSMENT_MANUAL = 'severity-assessment/manual';
    private const SEVERITY_ASSESSMENT_AUTOMATIC_LOCAL = 'severity-assessment/automatic/local';
    private const DIAGNOSIS_SUPPORT_ENDPOINT = 'diagnosis-support';
    private const LOGIN = 'login';

    public function __construct(private HttpClientInterface $httpClient) {}

    public static function createWithBaseUri(string $baseUri): self
    {
        return new self(HttpClient::createForBaseUri($baseUri));
    }

    public static function createWithHttpClient(HttpClientInterface $httpClient): self
    {
        return new self($httpClient);
    }

    public function login(string $username, string $password): AccessToken
    {
        $loginResponse = $this->httpClient->request(
            'POST',
            self::LOGIN,
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'body' => [
                    "username" => $username,
                    "password" => $password
                ]
            ]
        );
        if ($loginResponse->getStatusCode() !== 200) {
            throw new LoginException('Error while logging in the user', $loginResponse->getStatusCode());
        }
        $json = $loginResponse->toArray(false);

        return new AccessToken($json['access_token'], $json['token_type'], $json['expires_in_minutes']);
    }

    /**
     * @throws RequestException
     */
    public function severityAssessmentAutomaticLocal(SeverityAssessmentAutomaticLocalArguments $arguments, BearerToken $bearerToken, ?RequestOptions $requestOptions = null): SeverityAssessmentResponse
    {
        $json = $this->send($arguments, self::SEVERITY_ASSESSMENT_AUTOMATIC_LOCAL, $bearerToken, $requestOptions);
        return SeverityAssessmentResponse::fromJson($json);
    }

    /**
     * @throws RequestException
     */
    public function severityAssessmentManual(SeverityAssessmentManualArguments $arguments, BearerToken $bearerToken, ?RequestOptions $requestOptions = null): SeverityAssessmentResponse
    {
        $json = $this->send($arguments, self::SEVERITY_ASSESSMENT_MANUAL, $bearerToken, $requestOptions);
        return SeverityAssessmentResponse::fromJson($json);
    }

    /**
     * @throws RequestException
     */
    public function diagnosisSupport(DiagnosisSupportArguments $arguments, BearerToken $bearerToken, ?RequestOptions $requestOptions = null): DiagnosisSupportResponse
    {
        $json = $this->send($arguments, self::DIAGNOSIS_SUPPORT_ENDPOINT, $bearerToken, $requestOptions);
        return DiagnosisSupportResponse::createFromJson($json);
    }

    /**
     * @throws RequestException
     */
    private function send(MedicalDeviceArguments $arguments, string $path, BearerToken $bearerToken, ?RequestOptions $requestOptions = null): array
    {
        try {
            $response = $this->httpClient->request('POST', $path, [
                'json' => $arguments,
                'headers' => [
                    'Authorization' => $bearerToken->asAuthorizationHeader()
                ],
                ...($requestOptions?->asArray() ?? [])
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                throw new RequestException(
                    sprintf('Request failed with status code %d for path %s', $statusCode, $path),
                    statusCode: $statusCode,
                    content: $response->toArray(false)
                );
            }
            return $response->toArray();
        } catch (Throwable $exception) {
            if ($exception instanceof RequestException) {
                throw $exception;
            }
            throw new RequestException(
                sprintf('An error occurred while sending the request: %s', $exception->getMessage())
            );
        }
    }
}
