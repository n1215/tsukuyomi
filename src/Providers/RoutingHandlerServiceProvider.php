<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Providers;

use Illuminate\Container\Container;
use N1215\Http\Router\RouterInterface;
use N1215\Http\Router\RoutingErrorResponderInterface;
use N1215\Tsukuyomi\RoutingHandler;
use N1215\Tsukuyomi\RoutingHandlerInterface;

class RoutingHandlerServiceProvider
{
    public function register(Container $container)
    {
        $container->singleton(RoutingHandlerInterface::class, function (Container $container) {
            return new RoutingHandler(
                $container->get(RouterInterface::class),
                $container->get(RoutingErrorResponderInterface::class)
            );
        });
    }
}
