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

namespace ahmedghanem00\TempNumberClient\Result;

/**
 *
 */
readonly class CountryServiceInfoResult extends AbstractResult
{
    /**
     * @var array
     */
    private array $serviceData;

    /**
     * @param array $resultData
     */
    public function __construct(array $resultData)
    {
        parent::__construct($resultData);

        $this->serviceData = current($this->resultData['services']);
    }

    /**
     * @return string
     */
    public function getServiceId(): string
    {
        return $this->serviceData['serviceId'];
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceData['serviceName'];
    }

    /**
     * @return string
     */
    public function getServiceIconPath(): string
    {
        return $this->getBaseUrl() . $this->serviceData['serviceIconPath'];
    }

    /**
     * @return string
     */
    private function getBaseUrl(): string
    {
        return $this->resultData['iconBaseUrl'];
    }

    /**
     * @return string
     */
    public function getCountryId(): string
    {
        return $this->serviceData['countryId'];
    }

    /**
     * @return string
     */
    public function getCountryName(): string
    {
        return $this->serviceData['countryName'];
    }

    /**
     * @return string
     */
    public function getCountryIconPath(): string
    {
        return $this->getBaseUrl() . $this->serviceData['countryIconPath'];
    }

    /**
     * @return bool
     */
    public function isVirtual(): bool
    {
        return $this->serviceData['isVirtual'];
    }

    /**
     * @return string
     */
    public function getSnippet(): string
    {
        return $this->serviceData['snippet'];
    }

    /**
     * @return string
     */
    public function getWarning(): string
    {
        return $this->serviceData['warning'];
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->serviceData['price'];
    }

    /**
     * @return bool
     */
    public function hasNumbers(): bool
    {
        return $this->serviceData['hasNumbers'];
    }
}
