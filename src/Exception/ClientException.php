<?php declare(strict_types=1);
/*
 * This file is part of the TempNumberClient package.
 *
 * (c) Ahmed Ghanem <ahmedghanem7361@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ahmedghanem00\TempNumberClient\Exception;

use RuntimeException;
use Throwable;

class ClientException extends RuntimeException
{
    private array $additionalData;

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null, array $additionalData = [])
    {
        $this->additionalData = $additionalData;

        parent::__construct($message, $code, $previous);
    }

    public function getAdditionalData(string $key = null): mixed
    {
        return $key ? $this->additionalData[$key] : $this->additionalData;
    }

    public function addToAdditionalData(string $key, mixed $value): void
    {
        $this->additionalData[$key] = $value;
    }
}
