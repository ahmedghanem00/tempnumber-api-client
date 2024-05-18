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

namespace ahmedghanem00\TempNumberClient\Exception\API;

/**
 *
 * Example: 100 activations requested for Whatsapp in Germany but only 1 activation got sms.
 * This is 1% delivery success rate. No more activations allowed in 24 hours to Whatsapp in the Germany.
 *
 */
class LowSuccessRateException extends APIException implements TemporaryErrorInterface
{
    /**
     * @return int
     */
    public function retryAfter(): int
    {
        return 24 * 60 * 60;
    }
}
