<?php declare(strict_types=1);
/*
 * This file is part of the TempNumberClient package.
 *
 * (c) Ahmed Ghanem <ahmedghanem7361@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ahmedghanem00\TempNumberClient\Exception\API;

use ahmedghanem00\TempNumberClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 *
 */
class APIException extends ClientException
{
    public const Description = "API Failed Action";

    /**
     * @param ResponseInterface $response
     */
    public function __construct(
        private ResponseInterface $response
    )
    {
        parent::__construct(static::Description);
    }

    /**
     * @param string $errorName
     * @param ResponseInterface $response
     * @return static
     */
    public static function newFromErrorName(string $errorName, ResponseInterface $response): static
    {
        $exceptionClass = match (strtolower($errorName)) {
            'unauthorizedexception' => UnauthorizedServiceException::class,
            'invalidrequestparamsexception' => InvalidRequestParamsException::class,
            'paymentrequired' => PaymentRequiredException::class,
            'accountonholdexception' => AccountOnHoldException::class,
            'resourcenotfoundexception' => ResourceNotFoundException::class,
            'resourcebadstateexception' => ResourceBadStateException::class,
            'toomanyactivationspendingexception' => TooManyActivationsPendingException::class,
            'toomanyrequestsexception' => TooManyRequestsException::class,
            'expectedpriceerrorexception' => ExpectedPriceException::class,
            'goneexception' => GoneException::class,
            'lowsuccessrateexception' => LowSuccessRateException::class,
            'serviceunavailableexception' => ServiceUnavailableException::class,

            default => self::class
        };

        return new $exceptionClass($response);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getResultData(): array
    {
        return $this->getResponse()->toArray(false);
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
