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

use ahmedghanem00\TempNumberClient\Exception\ClientException;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 */
readonly class ServiceResult extends AbstractResult
{
    /**
     * @return float
     */
    public function getPrice(): float
    {
        if (!key_exists('p', $this->resultData)) {
            throw new ClientException("Price element is not found within the service ( {$this->getName()} ) result");
        }

        return $this->resultData['p'];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->resultData['s'];
    }

    /**
     * @return ArrayCollection<int, CountryResult>
     */
    public function countries(): ArrayCollection
    {
        if (!key_exists('countries', $this->resultData)) {
            throw new ClientException("Countries element is not found within the service ( {$this->getName()} ) result");
        }

        return new ArrayCollection(
            array_map(
                function (array $countryData) {
                    return new CountryResult($countryData);
                },
                $this->resultData['countries']
            )
        );
    }
}
