<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

use Psr\Http\Message\ServerRequestInterface;

interface HttpApplicationInterface
{
    public function run(ServerRequestInterface $request): void;
}
