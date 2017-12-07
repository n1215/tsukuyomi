<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class FrameworkTest extends TestCase
{
    public function test_getContainer()
    {
        /** @var ContainerInterface $container */
        $container = $this->createMock(ContainerInterface::class);
        $framework = new Framework($container, '/var/www/project');
        $this->assertSame($container, $framework->getContainer());
    }

    public function test_path()
    {
        /** @var ContainerInterface $container */
        $container = $this->createMock(ContainerInterface::class);
        $framework = new Framework($container, '/var/www/project');
        $this->assertEquals('/var/www/project/src', $framework->path('src'));
    }
}
