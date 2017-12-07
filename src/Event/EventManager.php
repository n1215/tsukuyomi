<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Event;

class EventManager implements EventManagerInterface
{
    /**
     * @var array
     *
     * ex)
     * [
     *     'eventName' => [
     *         ['priority' => 1, 'callback' => [self::class, 'methodName']]
     *     ],
     * ];
     */
    private $listenerMapping = [];

    /**
     * @inheritDoc
     */
    public function attach(string $event, callable $callback, int $priority = 0): bool
    {
        if (!isset($this->listenerMapping[$event])) {
            $this->listenerMapping[$event] = [];
        }

        $this->listenerMapping[$event][] = [
            'priority' => $priority,
            'callback' => $callback
        ];

        return true;
    }

    /**
     * @inheritDoc
     */
    public function detach(string $event, callable $callback): bool
    {
        foreach ($this->listenerMapping as $event => $listeners) {
            foreach ($listeners as $index => $listener) {
                if ($listener['callback'] === $callback) {
                    unset($listeners[$index]);
                }
            }
            $this->listenerMapping[$event] = array_values($listeners);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function clearListeners(string $event): void
    {
        if (isset($this->listenerMapping[$event])) {
            unset($this->listenerMapping[$event]);
        }
    }

    /**
     * @inheritDoc
     */
    public function trigger($event, $target = null, array $argv = [])
    {
        if (is_string($event)) {
            $event = new Event($event, $target, $argv);
        }

        $listeners = $this->getListenersForEvent($event);

        foreach ($listeners as $listener) {
            call_user_func($listener['callback'], $event);
            if ($event->isPropagationStopped()) {
                return;
            }
        }
    }

    private function getListenersForEvent(EventInterface $event): array
    {
        $eventName = $event->getName();

        if (!isset($this->listenerMapping[$eventName])) {
            return [];
        }

        $listeners = $this->listenerMapping[$eventName];

        uasort($listeners, function ($a, $b) {
            if ($a['priority'] === $b['priority']) {
                return 0;
            }
            return $a['priority'] < $b['priority'] ? -1 : 1;
        });

        return $listeners;
    }
}
