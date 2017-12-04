<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

use N1215\Jugoya\RequestHandlerFactory;
use Psr\Container\ContainerInterface;
use Psr\EventManager\EventManagerInterface;
use Zend\Diactoros\Response\SapiEmitter;

class Framework implements FrameworkInterface
{
    /** @var ContainerInterface  */
    private $container;

    /** @var string  */
    private $rootPath;

    public function __construct(ContainerInterface $container, string $rootPath)
    {
        $this->container = $container;
        $this->rootPath = $rootPath;
    }

    public function buildApplication(): HttpApplicationInterface
    {
        $handlerFactory = $this->container->get(RequestHandlerFactory::class);
        $coreHandler = $this->container->get(RoutingHandlerInterface::class);
        $middlewareConfigPath = $this->path('config/middlewares.php');
        $middlewareClasses = require $middlewareConfigPath;
        $requestHandler = $handlerFactory->create($coreHandler, $middlewareClasses);

        return new HttpApplication(
            $this->container->get(BootLoaderInterface::class),
            $requestHandler,
            new SapiEmitter(),
            $this->container->get(EventManagerInterface::class)
        );
    }

    public function path(string $relativePath): string
    {
        return $this->rootPath . $relativePath;
    }
}
