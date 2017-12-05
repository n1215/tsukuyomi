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
    public function attach($event, $callback, $priority = 0)
    {
        if (!is_string($event)) {
            throw new \InvalidArgumentException('$event must be a string.');
        }

        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('$callback must be a callable.');
        }

        if (!is_int($priority)) {
            throw new \InvalidArgumentException('$priority must be an integer.');
        }

        if (!isset($this->listenerMapping[$event])) {
            $this->listenerMapping[$event] = [];
        }

        $this->listenerMapping[$event][] = [
            'priority' => $priority,
            'callback' => $callback
        ];
    }

    /**
     * @inheritDoc
     */
    public function clearListeners($event)
    {
        if (!is_string($event)) {
            throw new \InvalidArgumentException('$event must be a string.');
        }

        if (isset($this->listenerMapping[$event])) {
            unset($this->listenerMapping[$event]);
        }
    }

    /**
     * @inheritDoc
     */
    public function detach($event, $callback)
    {
        if (!is_string($event)) {
            throw new \InvalidArgumentException('$event must be a string.');
        }

        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('$callback must be a callable.');
        }

        foreach ($this->listenerMapping as $event => $listeners) {
            foreach ($listeners as $index => $listener) {
                if ($listeners['callback'] === $callback) {
                    unset($listeners[$index]);
                }
            }
            $this->listenerMapping[$event] = array_values($listeners);
        }
    }

    /**
     * @inheritDoc
     */
    public function trigger($event, $target = null, $argv = array())
    {
        if (!$event instanceof EventInterface && !is_string($event)) {
            throw new \InvalidArgumentException('$event must be a string or an instance of EventInterface.');
        }

        if (is_string($event)) {
            $event = new Event($event, $target, $argv);
        }

        $eventName = $event->getName();

        if (!isset($this->listenerMapping[$eventName])) {
            return;
        }

        $listeners = $this->listenerMapping[$eventName];

        uasort($listeners, function ($a, $b) {
            if ($a['priority'] === $b['priority']) {
                return 0;
            }
            return $a['priority'] < $b['priority'] ? -1 : 1;
        });

        foreach ($listeners as $listener) {
            call_user_func($listener['callback'], $event);
            if ($event->isPropagationStopped()) {
                return;
            }
        }
    }
}
