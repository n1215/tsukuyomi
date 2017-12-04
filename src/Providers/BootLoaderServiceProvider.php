<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Providers;

use Illuminate\Container\Container;
use N1215\Tsukuyomi\BootLoader;
use N1215\Tsukuyomi\BootLoaderInterface;
use N1215\Tsukuyomi\FrameworkInterface;

class BootLoaderServiceProvider
{
    public function register(Container $container)
    {
        $container->singleton(BootLoaderInterface::class, function (Container $container) {
            /** @var FrameworkInterface $framework */
            $framework = $container->get(FrameworkInterface::class);
            $bootstrapConfigPath = $framework->path('config/bootstrappers.php');
            $bootstrapperClasses = require $bootstrapConfigPath;
            return new BootLoader($bootstrapperClasses);
        });
    }
}
