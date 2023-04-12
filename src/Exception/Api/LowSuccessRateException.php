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
 * Example: 100 activations requested for Whatsapp in Germany but only 1 activation got sms.
 * This is 1% delivery success rate. No more activations allowed in 24 hours to Whatsapp in the Netherlands.
 *
 */
class LowSuccessRateException extends ApiException implements TemporaryExceptionInterface
{
    /**
     *
     */
    public function __construct()
    {
        ClientException::__construct("Activations low success rate");
    }

    /**
     * @return int
     */
    public function retryAfter(): int
    {
        return 24 * 60 * 60;
    }
}
