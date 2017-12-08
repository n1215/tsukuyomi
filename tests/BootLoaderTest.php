<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

use PHPUnit\Framework\TestCase;

class BootLoaderTest extends TestCase
{
    public static $result = [];

    public function test_boot()
    {
        self::$result = [];
        $bootstrapperClasses = [
            HogeBootstrapper::class,
            FugaBootstrapper::class,
        ];
        $bootLoader = new BootLoader($bootstrapperClasses);

        $bootLoader->boot();

        $this->assertEquals($bootstrapperClasses, self::$result);
    }


    public function test_constructor_throws_exception_when_non_bootstrapper_class_given()
    {
        $bootstrapperClasses = [
            NonBootstrapper::class,
        ];
        $this->expectException(\InvalidArgumentException::class);

        new BootLoader($bootstrapperClasses);
    }
}

class HogeBootstrapper implements BootstrapperInterface
{
    public function bootstrap(): void
    {
        BootLoaderTest::$result[] = self::class;
    }
}

class FugaBootstrapper implements BootstrapperInterface
{
    public function bootstrap(): void
    {
        BootLoaderTest::$result[] = self::class;
    }
}


class NonBootstrapper
{
    public function bootstrap(): void
    {
    }
}
