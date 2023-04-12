<?php declare(strict_types=1);
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
readonly class CountryResult extends AbstractResult
{
    /**
     * @return float
     */
    public function getPrice(): float
    {
        if (!key_exists('p', $this->resultData)) {
            throw new ClientException("Price element is not found within the country ( {$this->getName()} ) result");
        }

        return $this->resultData['p'];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->resultData['c'];
    }

    /**
     * @return ArrayCollection<int, ServiceResult>
     */
    public function services(): ArrayCollection
    {
        if (!key_exists('services', $this->resultData)) {
            throw new ClientException("Services element is not found within the country ( {$this->getName()} ) result");
        }

        return new ArrayCollection(
            array_map(
                function (array $service) {
                    return new ServiceResult($service);
                },
                $this->resultData['services']
            )
        );
    }
}
