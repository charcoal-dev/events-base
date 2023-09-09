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
 * Class EventListenersTest
 */
class EventListenersTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     * @throws \Throwable
     */
    public function testListeners()
    {
        $registry = new \Charcoal\Events\EventsRegistry();
        $event1 = $registry->on("some.event");

        // Add listeners
        for ($i = 0; $i < 3; $i++) {
            $event1->listen(function () {
            });
        }

        $event2 = $registry->on("another.event");
        // Add listeners
        for ($i = 0; $i < 6; $i++) {
            $event2->listen(function () {
            });
        }


        $this->assertEquals(3, $event1->trigger(), "Event A listeners count");
        $this->assertEquals(6, $event2->trigger(), "Event B listeners count");
        $event1->purgeAllListeners();
        $this->assertEquals(0, $event1->trigger(), "Event A listeners zero");
        $this->assertEquals(6, $event2->trigger(), "Event B listeners still 6");
        $event2->purgeAllListeners();
        $this->assertEquals(0, $event1->trigger(), "Event A listeners 0");
        $this->assertEquals(0, $event2->trigger(), "Event B listeners 0");
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testCallbackArguments()
    {
        $registry = new \Charcoal\Events\EventsRegistry();
        $event1 = $registry->on("some.event");

        $event1->listen(function (string $msg, int $code, \Charcoal\Events\Event $event) {
            $this->assertEquals("Simple string match", $msg, "Argument 1");
            $this->assertEquals(0xffff, $code, "Argument 2");
            $this->assertInstanceOf(\Charcoal\Events\Event::class, $event, "Argument 3");
        });

        $event1->trigger(["Simple string match", 65535]);
    }
}
