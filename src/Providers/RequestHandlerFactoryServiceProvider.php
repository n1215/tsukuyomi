<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Providers;

use Illuminate\Container\Container;
use N1215\Jugoya\RequestHandlerFactoryInterface;
use N1215\Jugoya\LazyRequestHandlerFactory;

class RequestHandlerFactoryServiceProvider
{
    public function register(Container $container)
    {
        $container->singleton(RequestHandlerFactoryInterface::class, function (Container $container) {
            return LazyRequestHandlerFactory::fromContainer($container);
        });
    }
}
