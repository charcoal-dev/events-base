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
 * Class EventsNameNormalizeTest
 */
class EventsNameNormalizeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     */
    public function testInstanceHashes()
    {
        $registry = new \Charcoal\Events\EventsRegistry(\Charcoal\Events\ListenerThrowEnum::THROW_PREV);
        $event1 = $registry->on("test.event");
        $event1_id = spl_object_id($event1);

        $this->assertEquals($event1_id, spl_object_id($registry->on("Test.event")));
        $this->assertEquals($event1_id, spl_object_id($registry->on("Test.evenT")));
        $this->assertNotEquals($event1_id, spl_object_id($registry->on("Test_event")), "Expects different instance");
    }
}
