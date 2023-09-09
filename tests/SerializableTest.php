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

/**
 * Class SerializableTest
 */
class SerializableTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     * @throws \Throwable
     */
    public function testSerializeEvent(): void
    {
        $registry = new \Charcoal\Events\EventsRegistry();
        $event = $registry->on("an.event.to.be.remembered");

        // Added 4 listeners
        $event->listen(function () {
        });
        $event->listen(function () {
        });
        $event->listen(function () {
        });
        $event->listen(function () {
        });

        // Basic check
        $this->assertEquals(4, $event->trigger());

        $registrySerialized = serialize($registry);
        unset($registry, $event);

        /** @var \Charcoal\Events\EventsRegistry $registry2 */
        $registry2 = unserialize($registrySerialized);
        $this->assertFalse($registry2->has("this.was.not.set"));
        $this->assertTrue($registry2->has("an.event.to.be.remembered"), "Event recovered");
        $this->assertEquals(0, $registry2->on("an.event.to.be.remembered")->trigger(), "All listeners were lost");
    }

    /**
     * @return void
     */
    public function testSerializeException(): void
    {
        $registry = new \Charcoal\Events\EventsRegistry();
        $event = $registry->on("try.to.remember");
        $event->listen(function () {
        });

        $event->purgeListenersOnSerialize = false;

        $this->expectExceptionMessage("Serialization of 'Closure' is not allowed");
        serialize($registry);
    }
}
