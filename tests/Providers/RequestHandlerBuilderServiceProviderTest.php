<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Providers;

use Illuminate\Container\Container;
use N1215\Jugoya\LazyRequestHandlerBuilder;
use N1215\Jugoya\RequestHandlerBuilderInterface;
use PHPUnit\Framework\TestCase;

class RequestHandlerBuilderServiceProviderTest extends TestCase
{
    public function test_register()
    {
        $container = new Container();
        $provider = new RequestHandlerBuilderServiceProvider();

        $provider->register($container);

        $this->assertInstanceOf(LazyRequestHandlerBuilder::class, $container->get(RequestHandlerBuilderInterface::class));
    }
}
