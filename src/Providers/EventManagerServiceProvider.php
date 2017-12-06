<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Providers;

use N1215\Tsukuyomi\Event\EventManager;
use Illuminate\Container\Container;
use N1215\Tsukuyomi\Event\EventManagerInterface;

abstract class EventManagerServiceProvider
{
    public function register(Container $container)
    {
        $container->singleton(EventManagerInterface::class, function () {
            $eventManager = new EventManager();
            $this->registerEvents($eventManager);
            return $eventManager;
        });
    }

    abstract protected function registerEvents(EventManagerInterface $eventManager);
}
