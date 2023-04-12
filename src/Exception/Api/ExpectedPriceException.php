<?php declare(strict_types=1);
/*
 * This file is part of the TempNumberClient package.
 *
 * (c) Ahmed Ghanem <ahmedghanem7361@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ahmedghanem00\TempNumberClient\Exception\Api;

use ahmedghanem00\TempNumberClient\Exception\ClientException;

class ExpectedPriceException extends ApiException
{
    public function __construct(
        private readonly float $newPrice
    )
    {
        ClientException::__construct("Activation price has changed to ( $newPrice ) which is greater than the expected");
    }

    public function getNewPrice(): float
    {
        return $this->newPrice;
    }
}
