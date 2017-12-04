<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

class BootLoader implements BootLoaderInterface
{
    /** @var string[] */
    private $bootstrapperClasses;

    /**
     * @param string[] $bootstrapperClasses
     */
    public function __construct(array $bootstrapperClasses)
    {
        $this->bootstrapperClasses = $bootstrapperClasses;
    }

    public function boot(): void
    {
        foreach($this->bootstrapperClasses as $bootstrapperClass) {
            $bootstrapper = new $bootstrapperClass();
            $bootstrapper->bootstrap();
        }
    }
}