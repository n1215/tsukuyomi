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
}

class HogeBootstrapper
{
    public function bootstrap()
    {
        BootLoaderTest::$result[] = self::class;
    }
}

class FugaBootstrapper
{
    public function bootstrap()
    {
        BootLoaderTest::$result[] = self::class;
    }
}
