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

use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 */
readonly class ActivationHistoryResult extends AbstractResult
{
    /**
     * @return ArrayCollection<int, ActivationResult>
     */
    public function activations(): ArrayCollection
    {
        return new ArrayCollection(
            array_map(
                function (array $activationData) {
                    return new ActivationResult($activationData);
                },
                $this->resultData['activations']
            )
        );
    }

    /**
     * @return int
     */
    public function getPageIndex(): int
    {
        return $this->resultData['page'];
    }

    /**
     * @return int
     */
    public function getCurrentLimit(): int
    {
        return $this->resultData['limit'];
    }

    /**
     * @return int
     */
    public function getPagesCount(): int
    {
        return $this->resultData['pages'];
    }

    /**
     * @return int
     */
    public function getActivationsCount(): int
    {
        return $this->resultData['total'];
    }
}
