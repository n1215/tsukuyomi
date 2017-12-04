<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

use Illuminate\Container\Container;
use Psr\Container\ContainerInterface;

class ContainerBuilder implements ContainerBuilderInterface
{
    /** @var string[]  */
    private $providerClasses;

    /**
     * @param string[] $providerClasses
     */
    public function __construct(array $providerClasses)
    {
        $this->providerClasses = $providerClasses;
    }

    public function build(): ContainerInterface
    {
        $container = new Container();
        foreach ($this->providerClasses as $providerClass) {
            (new $providerClass())->register($container);
        }

        return $container;
    }
}
