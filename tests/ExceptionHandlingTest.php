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
 * Class ExceptionHandlingTest
 */
class ExceptionHandlingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     * @throws \Throwable
     */
    public function testExceptionIsThrown()
    {
        $event = $this->createRegistryEvent(\Charcoal\Events\ListenerThrowEnum::THROW_PREV);
        $this->expectException(RuntimeException::class);
        $event->trigger();
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testSilentDiscard()
    {
        $event = $this->createRegistryEvent(\Charcoal\Events\ListenerThrowEnum::SILENT_DISCARD);
        $this->assertEquals(2, $event->trigger(), "Total of 2 listeners callbacks");
    }

    /**
     * @param \Charcoal\Events\ListenerThrowEnum $flag
     * @return \Charcoal\Events\Event
     */
    private function createRegistryEvent(\Charcoal\Events\ListenerThrowEnum $flag): \Charcoal\Events\Event
    {
        $registry = new \Charcoal\Events\EventsRegistry($flag);
        $e1 = $registry->on("test.event");
        $e1->listen(function () {
            throw new RuntimeException('Listener 1 throws this');
        });

        $e1->listen(function () {
            throw new UnexpectedValueException('Listener 2 throws this');
        });

        return $e1;
    }
}
