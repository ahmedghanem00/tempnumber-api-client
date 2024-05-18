<?php
/*
 * This file is part of the TempNumberClient package.
 *
 * (c) Ahmed Ghanem <ahmedghanem7361@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);
/*
 * This file is part of the TempNumberClient package.
 *
 * (c) Ahmed Ghanem <ahmedghanem7361@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ahmedghanem00\TempNumberClient;

use ahmedghanem00\TempNumberClient\Enum\ActivationStatus;
use ahmedghanem00\TempNumberClient\Enum\Country;
use ahmedghanem00\TempNumberClient\Enum\Service;
use ahmedghanem00\TempNumberClient\Enum\TempNumberServer;
use ahmedghanem00\TempNumberClient\Exception\API\APIException;
use ahmedghanem00\TempNumberClient\Exception\ClientException;
use ahmedghanem00\TempNumberClient\Result\ActivationHistoryResult;
use ahmedghanem00\TempNumberClient\Result\ActivationResult;
use ahmedghanem00\TempNumberClient\Result\CountryResult;
use ahmedghanem00\TempNumberClient\Result\CountryServiceInfoResult;
use ahmedghanem00\TempNumberClient\Result\ServiceResult;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 *
 */
class Client
{
    /**
     * @var int
     */
    public const DefaultHttpClientTimeout = 15;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @param string $apiKey
     * @param HttpClientInterface|null $httpClient
     * @param TempNumberServer $backendServer
     */
    public function __construct(
        #[\SensitiveParameter]
        string               $apiKey,
        ?HttpClientInterface $httpClient = null,
        TempNumberServer     $backendServer = TempNumberServer::Production
    ) {
        $this->setHttpClient($httpClient ?? HttpClient::create());
        $this->setApiKey($apiKey);
        $this->setBackendServer($backendServer);
        $this->setHttpClientTimeout(self::DefaultHttpClientTimeout);
    }

    /**
     * @param string $apiKey
     * @return void
     */
    public function setApiKey(#[\SensitiveParameter] string $apiKey): void
    {
        $this->applyHttpClientOptions([
            'headers' => [
                'x-api-key' => $apiKey
            ]
        ]);
    }

    /**
     * @param array<string, string|array|int> $options
     * @return void
     */
    public function applyHttpClientOptions(array $options): void
    {
        $this->httpClient = $this->httpClient->withOptions($options);
    }

    /**
     * @param TempNumberServer $server
     * @return void
     */
    public function setBackendServer(TempNumberServer $server): void
    {
        $this->applyHttpClientOptions([
            'base_uri' => $server->value
        ]);
    }

    /**
     * @param int $timeout
     * @return void
     */
    public function setHttpClientTimeout(int $timeout): void
    {
        $this->applyHttpClientOptions([
            'timeout' => $timeout
        ]);
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function retrieveBalance(): float
    {
        $response = $this->httpClient->request('GET', 'user/balance');

        return $this->checkAndGetResultDataFromResponse($response)['balance'];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function checkAndGetResultDataFromResponse(ResponseInterface $response): array
    {
        $resultData = $response->toArray(false);

        if (@$errorName = $resultData['errorName']) {
            throw APIException::newFromErrorName($errorName, $response);
        }

        return $resultData;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function requestNewActivation(string|Service $service, string|Country $country, float $expectedPrice): ActivationResult
    {
        [$serviceId, $countryId] = [
            is_string($service) ? $service : $service->value,
            is_string($country) ? $country : $country->value
        ];

        $response = $this->httpClient->request('POST', 'activations', [
            'json' => [
                'serviceId' => $serviceId,
                'countryId' => $countryId,
                'expectedPrice' => $expectedPrice
            ]
        ]);

        return new ActivationResult($this->checkAndGetResultDataFromResponse($response));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function retryActivation(int $activationId): void
    {
        $response = $this->httpClient->request('PUT', "activations/$activationId", [
            'json' => [
                'status' => 'retry'
            ]
        ]);

        $this->checkAndGetResultDataFromResponse($response);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function retrieveActivationHistory(int $page = 1, int $limit = 10): ActivationHistoryResult
    {
        $response = $this->httpClient->request('GET', 'activations', [
            'query' => [
                'page' => $page,
                'limit' => $limit
            ]
        ]);

        return new ActivationHistoryResult($this->checkAndGetResultDataFromResponse($response));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function retrieveCountryServiceInfo(string|Service $service, string|Country $country): CountryServiceInfoResult
    {
        [$serviceId, $countryId] = [
            is_string($service) ? $service : $service->value,
            is_string($country) ? $country : $country->value
        ];

        $response = $this->httpClient->request('GET', "services/$serviceId/countries/$countryId");

        return new CountryServiceInfoResult($this->checkAndGetResultDataFromResponse($response));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function retrievePriceListByCountry(): ArrayCollection
    {
        return $this->retrievePriceList('country');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    private function retrievePriceList(string $retrieveType): ArrayCollection
    {
        $response = match ($retrieveType) {
            'country' => $this->httpClient->request('GET', 'services/pricelistByCountry'),
            'service' => $this->httpClient->request('GET', 'services/pricelistByService'),

            default => throw new Exception("Unhandled match case")
        };

        return new ArrayCollection(
            array_map(
                function (array $nodeData) use ($retrieveType) {
                    return match ($retrieveType) {
                        'country' => new CountryResult($nodeData),
                        'service' => new ServiceResult($nodeData),

                        default => throw new Exception("Unhandled match case")
                    };
                },
                $this->checkAndGetResultDataFromResponse($response)
            )
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function retrievePriceListByService(): ArrayCollection
    {
        return $this->retrievePriceList('service');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function waitForActivationStatus(int $activationId, ActivationStatus $desiredStatus, int $pollingInterval = 5, int $maxDuration = 300): ActivationResult
    {
        $startTime = time();

        while ((time() - $startTime) < $maxDuration) {
            $retrievedActivationData = $this->retrieveActivationData($activationId);
            $retrievedStatus = ActivationStatus::from($retrievedActivationData->getStatus());

            # Current status match the desired
            if ($retrievedStatus->value === $desiredStatus->value) {
                return $retrievedActivationData;
            }

            # The current status has a sequence order which is larger than the desired status
            # So, there's no any point of waiting ( It has been already reached and bypassed the desired status )
            if ($retrievedStatus->getSequence() > $desiredStatus->getSequence()) {
                throw new ClientException("The sequence order of the current status ( $retrievedStatus->name ) is larger than the desired status ( $desiredStatus->name )");
            }

            # The current status is in a final state. So, there's no any point of waiting as it will never get changed
            if ($retrievedStatus->getSequence() === 100) {
                throw new ClientException("The current status ( { $retrievedStatus->name } ) is in a final state");
            }

            sleep($pollingInterval);
        }

        throw new ClientException(vsprintf("Maximum duration has been reached while waiting for the status ( %s ) of activation ( %s )", [$desiredStatus->name, $activationId]));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function retrieveActivationData(int $activationId): ActivationResult
    {
        $response = $this->httpClient->request('GET', "activations/$activationId");

        return new ActivationResult($this->checkAndGetResultDataFromResponse($response));
    }
}
