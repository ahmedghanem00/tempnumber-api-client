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

/**
 *
 */
interface TemporaryExceptionInterface
{

    /**
     * @return int Seconds to wait before making next retry
     */
    public function retryAfter(): int;

}
