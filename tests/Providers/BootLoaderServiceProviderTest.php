<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Providers;

use Illuminate\Container\Container;
use N1215\Tsukuyomi\BootLoader;
use N1215\Tsukuyomi\BootLoaderInterface;
use N1215\Tsukuyomi\Framework;
use N1215\Tsukuyomi\FrameworkInterface;
use PHPUnit\Framework\TestCase;

class BootLoaderServiceProviderTest extends TestCase
{
    public function test_register()
    {
        $container = new Container();
        $container->bind(FrameworkInterface::class,function () use ($container) {
            return new Framework($container, __DIR__);
        });
        $provider = new BootLoaderServiceProvider();

        $provider->register($container);

        $this->assertInstanceOf(BootLoader::class, $container->get(BootLoaderInterface::class));
    }
}
