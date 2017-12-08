<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

class BootLoader implements BootLoaderInterface
{
    /** @var BootstrapperInterface[] */
    private $bootstrappers;

    /**
     * @param string[] $bootstrapperClasses
     */
    public function __construct(array $bootstrapperClasses)
    {
        $this->bootstrappers = [];
        foreach($bootstrapperClasses as $bootstrapperClass) {
            $this->addBootstrapper($bootstrapperClass);
        }
    }

    private function addBootstrapper(string $bootstrapperClass)
    {
        $bootstrapper = new $bootstrapperClass();
        if (!is_subclass_of($bootstrapperClass, BootstrapperInterface::class)) {
            throw new \InvalidArgumentException('bootstrapper must implement' . BootstrapperInterface::class . '.');
        }

        $this->bootstrappers[] = $bootstrapper;
    }

    public function boot(): void
    {
        foreach($this->bootstrappers as $bootstrapper) {
            $bootstrapper->bootstrap();
        }
    }
}
