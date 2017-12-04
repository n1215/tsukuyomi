<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Providers;

use Illuminate\Container\Container;
use N1215\Jugoya\RequestHandlerFactory;

class RequestHandlerFactoryServiceProvider
{
    public function register(Container $container)
    {
        $container->singleton(RequestHandlerFactory::class, function (Container $container) {
            return RequestHandlerFactory::fromContainer($container);
        });
    }
}
