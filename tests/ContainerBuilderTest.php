<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;

class ContainerBuilderTest extends TestCase
{
    public function test_boot()
    {
        $containerBuilder = new ContainerBuilder([
            HogeServiceProvider::class,
            FugaServiceProvider::class,
        ]);

        $container = $containerBuilder->build();

        $this->assertInstanceOf(Container::class, $container);
        $this->assertEquals(HogeServiceProvider::class, $container->get('hoge'));
        $this->assertEquals(FugaServiceProvider::class, $container->get('fuga'));
    }
}

class HogeServiceProvider
{
    public function register(Container $container)
    {
        $container->bind('hoge',  function () { return self::class; });
    }
}

class FugaServiceProvider
{
    public function register(Container $container)
    {
        $container->bind('fuga', function() { return self::class; });
    }
}
