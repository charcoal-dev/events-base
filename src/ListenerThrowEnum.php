<?php
/*
 * This file is a part of "charcoal-dev/events-base" package.
 * https://github.com/charcoal-dev/events-base
 *
 * Copyright (c) Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/charcoal-dev/events-base/blob/master/LICENSE
 */

declare(strict_types=1);

namespace Charcoal\Events;

/**
 * Class ListenerThrowEnum
 * @package Charcoal\Events
 */
enum ListenerThrowEnum
{
    // Throw any caught instance of \Throwable while executing listener callbacks (Default)
    case THROW_PREV;
    // Silently discard caught exceptions
    case SILENT_DISCARD;
}