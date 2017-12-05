<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

use Psr\Container\ContainerInterface;

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

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function path(string $relativePath): string
    {
        return $this->rootPath . $relativePath;
    }
}
