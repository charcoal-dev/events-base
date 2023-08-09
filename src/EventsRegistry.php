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
 * Class EventsRegistry
 * @package Charcoal\Events
 */
class EventsRegistry
{
    private array $events = [];

    /**
     * @param \Charcoal\Events\ListenerThrowEnum $listenerExHandling
     */
    public function __construct(
        public readonly ListenerThrowEnum $listenerExHandling = ListenerThrowEnum::THROW_PREV,
    )
    {
    }

    /**
     * Create a new Event instance or retrieves existing one.
     * @param string $event
     * @return Event
     */
    public function on(string $event): Event
    {
        $event = $this->normalizeEventName($event);
        if (array_key_exists($event, $this->events)) {
            return $this->events[$event];
        }

        return $this->events[$event] = new Event($this, $event);
    }

    /**
     * @param string $event
     * @return bool
     */
    public function has(string $event): bool
    {
        return array_key_exists($this->normalizeEventName($event), $this->events);
    }

    /**
     * @param \Charcoal\Events\Event|string $event
     * @return void
     */
    public function clear(Event|string $event): void
    {
        unset($this->events[$this->normalizeEventName($event instanceof Event ? $event->name : $event)]);
    }

    /**
     * @param string $name
     * @return string
     */
    private function normalizeEventName(string $name): string
    {
        return strtolower($name);
    }
}