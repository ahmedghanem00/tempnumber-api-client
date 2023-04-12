<?php declare(strict_types=1);
/*
 * This file is part of the TempNumberClient package.
 *
 * (c) Ahmed Ghanem <ahmedghanem7361@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ahmedghanem00\TempNumberClient\Enum;

/**
 *
 */
enum ActivationStatus: string
{
    case NUMBER_REQUESTED = 'numberRequested';

    case NUMBER_RECEIVED = 'numberReceived';

    case SMS_REQUESTED = 'smsRequested';

    case SMS_RECEIVED = 'smsReceived';

    case RETRY_REQUESTED = 'retryRequested';

    case RETRY_RECEIVED = 'retryReceived';

    case SERVICE_CONSUMED = 'serviceConsumed';

    case PERMANENT_ERROR = 'error';

    case REFUNDED = 'refunded';

    public function getSequence(): int
    {
        return match ($this) {
            self::NUMBER_REQUESTED => 1,
            self::NUMBER_RECEIVED => 2,
            self::SMS_REQUESTED => 3,
            self::SMS_RECEIVED => 4,
            self::RETRY_REQUESTED => 5,
            self::RETRY_RECEIVED => 6,
            self::SERVICE_CONSUMED, self::REFUNDED, self::PERMANENT_ERROR => 100, # Final Status
        };
    }
}
