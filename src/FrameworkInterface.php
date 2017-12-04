<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

interface FrameworkInterface
{
    public function buildApplication(): HttpApplicationInterface;

    public function path(string $relativePath): string;
}
