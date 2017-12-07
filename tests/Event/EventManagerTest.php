<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Event;

use PHPUnit\Framework\TestCase;

class EventManagerTest extends TestCase
{
    public function test_trigger_calls_attached_listener()
    {
        $eventManager = new EventManager();
        $eventName = 'dummy.event';
        $event = new Event($eventName);
        $callback = function (EventInterface $callbackEvent) use ($event) {
            $this->assertSame($event, $callbackEvent);
            return;
        };

        $result = $eventManager->attach($eventName, $callback);
        $eventManager->trigger($event);

        $this->assertTrue($result);
    }

    public function test_trigger_can_call_not_detached_listener()
    {
        $eventManager = new EventManager();
        $eventName = 'dummy.event';
        $event = new Event($eventName);

        $callbackMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['call'])
            ->getMock();

        $callbackMock->expects($this->once())
            ->method('call')
            ->with($event);

        $callbackMockToDetach = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['call'])
            ->getMock();

        $callbackMockToDetach->expects($this->never())
            ->method('call')
            ->with($this->anything());

        $eventManager->attach($eventName, [$callbackMock, 'call']);
        $eventManager->attach($eventName, [$callbackMockToDetach, 'call']);
        $eventManager->detach($eventName, [$callbackMockToDetach, 'call']);
        $eventManager->trigger($event);
    }

    public function test_trigger_doesnt_call_detached_listener()
    {
        $eventManager = new EventManager();
        $eventName = 'dummy.event';
        $event = new Event($eventName);

        $callbackMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['call'])
            ->getMock();

        $callbackMock->expects($this->never())
            ->method('call')
            ->with($this->anything());

        $eventManager->attach($eventName, [$callbackMock, 'call']);
        $result = $eventManager->detach($eventName, [$callbackMock, 'call']);
        $eventManager->trigger($event);

        $this->assertTrue($result);
    }

    public function test_trigger_doesnt_call_cleared_listener(): void
    {
        $eventManager = new EventManager();
        $eventName = 'dummy.event';
        $event = new Event($eventName);

        $callbackMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['call'])
            ->getMock();

        $callbackMock->expects($this->never())
            ->method('call')
            ->with($this->anything());

        $eventManager->attach($eventName, [$callbackMock, 'call']);
        $eventManager->clearListeners($eventName);
        $eventManager->trigger($event);
    }

    public function test_trigger_can_call_not_cleared_listener(): void
    {
        $eventManager = new EventManager();

        $eventName = 'dummy.event';
        $event = new Event($eventName);

        $callbackMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['call'])
            ->getMock();

        $eventToClearName = 'cleared.event';

        $clearCallbackMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['call'])
            ->getMock();

        $clearCallbackMock->expects($this->never())
            ->method('call')
            ->with($this->anything());

        $eventManager->attach($eventName, [$callbackMock, 'call']);
        $eventManager->attach($eventToClearName, [$clearCallbackMock, 'call']);
        $eventManager->clearListeners($eventToClearName);
        $eventManager->trigger($event);
    }

    public function test_trigger_can_call_listeners_order_by_priority()
    {
        $eventManager = new EventManager();

        $listenerSettings = [
            ['id' => 1, 'priority' => 4],
            ['id' => 2, 'priority' => 1],
            ['id' => 3, 'priority' => 3],
            ['id' => 4, 'priority' => 2],
        ];

        $eventName = 'dummy.event';
        $results = [];
        array_walk($listenerSettings, function (array $listenerSetting) use ($eventManager, $eventName, &$results) {
            $id = $listenerSetting['id'];
            $callback = function (EventInterface $event) use (&$results, $id) {
                $results[] = $id;
            };

            $eventManager->attach($eventName, $callback, $listenerSetting['priority']);
        });

        $eventManager->trigger(new Event($eventName));

        $this->assertEquals([2, 4, 3, 1], $results);
    }

    public function test_trigger_can_stop_event_propagation()
    {
        $eventManager = new EventManager();

        $listenerSettings = [
            ['id' => 1, 'priority' => 4, 'stop' => false],
            ['id' => 2, 'priority' => 1, 'stop' => false],
            ['id' => 3, 'priority' => 3, 'stop' => false],
            ['id' => 4, 'priority' => 2, 'stop' => true],
        ];

        $eventName = 'dummy.event';
        $results = [];
        array_walk($listenerSettings, function (array $listenerSetting) use ($eventManager, $eventName, &$results) {
            $id = $listenerSetting['id'];
            $stop = $listenerSetting['stop'];
            $callback = function (EventInterface $event) use (&$results, $id, $stop) {
                $results[] = $id;
                if ($stop) {
                    $event->stopPropagation(true);
                }
            };

            $eventManager->attach($eventName, $callback, $listenerSetting['priority']);
        });

        $eventManager->trigger(new Event($eventName));

        $this->assertEquals([2, 4], $results);
    }
}
