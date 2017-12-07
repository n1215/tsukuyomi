<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Providers;

use Illuminate\Container\Container;
use N1215\Http\Router\RouterInterface;
use N1215\Http\Router\RoutingErrorResponderInterface;
use N1215\Http\Router\RoutingHandler;
use N1215\Http\Router\RoutingHandlerInterface;
use N1215\Jugoya\RequestHandlerBuilderInterface;
use N1215\Tsukuyomi\BootLoaderInterface;
use N1215\Tsukuyomi\FrameworkInterface;
use N1215\Tsukuyomi\HttpApplication;
use N1215\Tsukuyomi\HttpApplicationInterface;
use N1215\Tsukuyomi\Event\EventManagerInterface;
use Zend\Diactoros\Response\SapiEmitter;

class HttpApplicationServiceProvider
{
    public function register(Container $container)
    {
        $container->singleton(RoutingHandlerInterface::class, function (Container $container) {
            return new RoutingHandler(
                $container->get(RouterInterface::class),
                $container->get(RoutingErrorResponderInterface::class)
            );
        });

        $container->singleton(HttpApplicationInterface::class, function (Container $container) {
            /** @var RequestHandlerBuilderInterface $handlerBuilder */
            $handlerBuilder = $container->get(RequestHandlerBuilderInterface::class);
            /** @var FrameworkInterface $framework */
            $framework = $container->get(FrameworkInterface::class);
            $middlewareConfigPath = $framework->path('config/middlewares.php');
            $middlewareClasses = require $middlewareConfigPath;
            $requestHandler = $handlerBuilder->build(RoutingHandlerInterface::class, $middlewareClasses);

            return new HttpApplication(
                $container->get(BootLoaderInterface::class),
                $requestHandler,
                new SapiEmitter(),
                $container->get(EventManagerInterface::class)
            );
        });
    }
}
