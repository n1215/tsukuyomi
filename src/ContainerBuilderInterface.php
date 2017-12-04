<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

use Psr\Container\ContainerInterface;

interface ContainerBuilderInterface
{
    public function build(): ContainerInterface;
}