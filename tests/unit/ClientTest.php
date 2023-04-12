<?php declare(strict_types=1);
/*
 * This file is part of the TempNumberClient package.
 *
 * (c) Ahmed Ghanem <ahmedghanem7361@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ahmedghanem00\TempNumberClient\Tests\unit;

use ahmedghanem00\TempNumberClient\Client;
use ahmedghanem00\TempNumberClient\Enum\Country;
use ahmedghanem00\TempNumberClient\Enum\Service;
use ahmedghanem00\TempNumberClient\Enum\TempNumberServer;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @covers Client
 */
class ClientTest extends TestCase
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @covers Client::retrieveBalance
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testRetrieveBalance()
    {
        $this->assertIsFloat($this->client->retrieveBalance());
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testRequestNewActivation()
    {
        $activation = $this->client->requestNewActivation(Service::Facebook, Country::United_States, 0.4);

        # Activation
        $this->assertIsInt($activation->getId());
        $this->assertIsString($activation->getStatus());
        $this->assertIsInt($activation->getRating());
        $this->assertIsInt($activation->getReason());
        $this->assertIsFloat($activation->getPrice());
        $this->assertIsBool($activation->isRetryable());
        $this->assertIsInt($activation->getCreationTimestamp());
        $this->assertIsInt($activation->getExpireTimestamp());
        $this->assertIsInt($activation->getCurrentTimestamp());
        $this->assertIsInt($activation->getRemainingSecondsToExpire());

        # Number
        $this->assertIsInt($activation->getPhoneNumberCountryCode());
        $this->assertIsInt($activation->getPhoneNumberWithCountryCode());
        $this->assertIsInt($activation->getPhoneNumberWithoutCountryCode());
        $this->assertIsString($activation->getFormattedPhoneNumberWithCountryCode());

        # Message
        $this->assertIsString($activation->getReceivedSMS());
        $this->assertIsString($activation->getDetectedOtpCodeFromReceivedSMS());

        # Service
        $this->assertIsString($activation->getServiceId());
        $this->assertIsString($activation->getServiceName());
        $this->assertIsString($activation->getServiceIconPath());

        # Country
        $this->assertIsString($activation->getCountryId());
        $this->assertIsString($activation->getCountryName());
        $this->assertIsString($activation->getCountryIconPath());
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testRetryActivation()
    {
        try {
            $this->client->retryActivation(7378322);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testRetrieveActivationHistory()
    {
        $result = $this->client->retrieveActivationHistory($requestedPageIndex = 1, $requestedLimit = 10);

        $this->assertEquals($requestedPageIndex, $result->getPageIndex());
        $this->assertEquals($requestedLimit, $result->getCurrentLimit());
        $this->assertIsInt($result->getActivationsCount());
        $this->assertIsInt($result->getPagesCount());

        foreach ($result->activations()->getIterator() as $activation) {
            $this->assertIsInt($activation->getId());
            $this->assertIsInt($activation->getCreationTimestamp());
            $this->assertIsInt($activation->getExpireTimestamp());
            $this->assertIsInt($activation->getCurrentTimestamp());
            $this->assertIsInt($activation->getRating());
            $this->assertIsInt($activation->getReason());

            $this->assertIsFloat($activation->getPrice());

            $this->assertIsString($activation->getStatus());
            $this->assertIsString($activation->getServiceId());
            $this->assertIsString($activation->getServiceName());
            $this->assertIsString($activation->getServiceIconPath());
            $this->assertIsString($activation->getCountryId());
            $this->assertIsString($activation->getCountryName());
            $this->assertIsString($activation->getCountryIconPath());
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testRetrieveServiceInfoInCountry()
    {
        $result = $this->client->retrieveCountryServiceInfo($requestedServiceId = 'facebook', $requestedCountryId = 'be');

        $this->assertEquals($requestedCountryId, $result->getCountryId());
        $this->assertIsString($result->getCountryName());
        $this->assertIsString($result->getCountryIconPath());

        $this->assertEquals($requestedServiceId, $result->getServiceId());
        $this->assertIsString($result->getServiceName());
        $this->assertIsString($result->getServiceIconPath());

        $this->assertIsFloat($result->getPrice());
        $this->assertIsString($result->getSnippet());
        $this->assertIsString($result->getWarning());

        $this->assertIsBool($result->hasNumbers());
        $this->assertIsBool($result->isVirtual());
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testRetrievePriceListByService()
    {
        $this->markTestSkipped(); ## TEMP
        $result = $this->client->retrievePriceListByService();

        foreach ($result->getIterator() as $service) {
            $this->assertIsString($service->getName());

            foreach ($service->countries()->getIterator() as $country) {
                $this->assertIsString($country->getName());
                $this->assertIsFloat($country->getPrice());
            }
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testRetrievePriceListByCountry()
    {
        $this->markTestSkipped(); ## TEMP
        $result = $this->client->retrievePriceListByCountry();

        foreach ($result->getIterator() as $country) {
            $this->assertIsString($country->getName());

            foreach ($country->services()->getIterator() as $service) {
                $this->assertIsString($service->getName());
                $this->assertIsFloat($service->getPrice());
            }
        }
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $backendServer = current(array_filter(TempNumberServer::cases(), function ($case) {
            return $case->name === getenv('BACKEND_SERVER');
        }));

        $this->client = new Client(getenv('API_KEY'), null, $backendServer);

        $this->client->applyHttpClientOptions([
            #'proxy' => '127.0.0.1:9090', 'verify_peer' => false,
        ]);
    }
}
