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

enum TempNumberServer: string
{
    case Production = 'https://tn-api.com/api/v1/';

    case Mock = 'https://mock.temp-number.org/v1/';
}
