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
 * Class Event
 * @package Charcoal\Events
 */
class Event
{
    /**
     * @param \Charcoal\Events\EventsRegistry $registry
     * @param string $name
     * @param array $listeners
     * @param bool $purgeListenersOnSerialize
     */
    public function __construct(
        public readonly EventsRegistry $registry,
        public readonly string         $name,
        private array                  $listeners = [],
        public bool                    $purgeListenersOnSerialize = true
    )
    {
    }

    /**
     * @return array
     */
    public function __serialize(): array
    {
        return [
            "registry" => $this->registry,
            "name" => $this->name,
            "listeners" => $this->purgeListenersOnSerialize ? [] : $this->listeners,
            "purgeListenersOnSerialize" => $this->purgeListenersOnSerialize
        ];
    }

    /**
     * @param array $data
     * @return void
     */
    public function __unserialize(array $data): void
    {
        $this->registry = $data["registry"];
        $this->name = $data["name"];
        $this->purgeListenersOnSerialize = $data["purgeListenersOnSerialize"];
        $this->listeners = $this->purgeListenersOnSerialize ? [] : $data["listeners"];
    }

    /**
     * @return void
     */
    public function purgeAllListeners(): void
    {
        $this->listeners = [];
    }

    /**
     * Binds a callback method to this event
     * @param callable $callback
     * @return $this
     */
    public function listen(callable $callback): static
    {
        $this->listeners[] = $callback;
        return $this;
    }

    /**
     * All bound callback methods (listeners) are triggered in order.
     * Arguments passed to this method will be carried forward to listening callbacks.
     * Instance of this Event class is also appended to list of arguments.
     * @param array $args
     * @param \Charcoal\Events\ListenerThrowEnum|null $listenerExHandling
     * @return int
     */
    public function trigger(array $args = [], ?ListenerThrowEnum $listenerExHandling = null): int
    {
        if (!$this->listeners) {
            return 0;
        }

        $throwHandle = $listenerExHandling ?? $this->registry->listenerExHandling;
        $args[] = $this;
        $count = 0;
        foreach ($this->listeners as $listener) {
            try {
                call_user_func_array($listener, $args);
            } catch (\Throwable $t) {
                if ($throwHandle === ListenerThrowEnum::THROW_PREV) {
                    throw $t;
                }
            }

            $count++;
        }

        return $count;
    }
}