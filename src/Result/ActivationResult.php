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
readonly class ActivationResult extends AbstractResult
{
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->resultData['id'];
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->resultData['status'];
    }

    /**
     * @return string
     */
    public function getCountryId(): string
    {
        return $this->resultData['countryId'];
    }

    /**
     * @return string
     */
    public function getCountryName(): string
    {
        return $this->resultData['countryName'];
    }

    /**
     * @return string
     */
    public function getCountryIconPath(): string
    {
        return $this->getIconBaseUrl() . $this->resultData['countryIconPath'];
    }

    /**
     * @return string
     */
    private function getIconBaseUrl(): string
    {
        return $this->resultData['iconBaseUrl'];
    }

    /**
     * @return string
     */
    public function getServiceId(): string
    {
        return $this->resultData['serviceId'];
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->resultData['serviceName'];
    }

    /**
     * @return string
     */
    public function getServiceIconPath(): string
    {
        return $this->getIconBaseUrl() . $this->resultData['serviceIconPath'];
    }

    /**
     * @return int
     */
    public function getPhoneNumberWithCountryCode(): int
    {
        return $this->resultData['number'];
    }

    /**
     * @return int
     */
    public function getPhoneNumberWithoutCountryCode(): int
    {
        return $this->resultData['number'];
    }

    /**
     * @return string
     */
    public function getFormattedPhoneNumberWithCountryCode(): string
    {
        return $this->resultData['formatNumber'];
    }

    /**
     * @return int
     */
    public function getPhoneNumberCountryCode(): int
    {
        return $this->resultData['countryCode'];
    }

    /**
     * @return string
     */
    public function getReceivedSMS(): string
    {
        return $this->resultData['message'];
    }

    /**
     * @return string
     */
    public function getDetectedOtpCodeFromReceivedSMS(): string
    {
        return (string)$this->resultData['code'];
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->resultData['price'];
    }

    /**
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return $this->resultData['createdAt'];
    }

    /**
     * @return int
     */
    public function getRemainingSecondsToExpire(): int
    {
        return ($this->getExpireTimestamp() - $this->getCurrentTimestamp()) ?: 0;
    }

    /**
     * @return int
     */
    public function getExpireTimestamp(): int
    {
        return $this->resultData['numberExpireAt'];
    }

    /**
     * @return int
     */
    public function getCurrentTimestamp(): int
    {
        return $this->resultData['utc'];
    }

    /**
     * @return bool
     */
    public function isRetryable(): bool
    {
        return $this->resultData['retryAvailable'];
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->resultData['rating'];
    }

    /**
     * @return int
     */
    public function getReason(): int
    {
        return $this->resultData['reason'];
    }
}
