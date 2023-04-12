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

/**
 *
 */
class ResourceBadStateException extends ApiException implements TemporaryExceptionInterface
{
    /**
     *
     */
    public function __construct()
    {
        ClientException::__construct("Resource is in a bad state");
    }

    /**
     * @return int
     */
    public function retryAfter(): int
    {
        return 10 * 60;
    }
}
