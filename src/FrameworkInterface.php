<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

use Psr\Container\ContainerInterface;

interface FrameworkInterface
{
    public function getContainer(): ContainerInterface;

    public function path(string $relativePath): string;
}
