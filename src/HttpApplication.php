<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

use Interop\Http\Server\RequestHandlerInterface;
use N1215\Tsukuyomi\Event\AppTerminating;
use N1215\Tsukuyomi\Event\EventManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmitterInterface;

class HttpApplication implements HttpApplicationInterface
{
    /** @var BootLoaderInterface  */
    private $bootLoader;

    /** @var EventManagerInterface  */
    private $eventManager;

    /** @var RequestHandlerInterface  */
    private $requestHandler;

    /** @var EmitterInterface  */
    private $responseEmitter;

    public function __construct(
        BootLoaderInterface $bootLoader,
        RequestHandlerInterface $requestHandler,
        EmitterInterface $responseEmitter,
        EventManagerInterface $eventManager
    ) {
        $this->bootLoader = $bootLoader;
        $this->requestHandler = $requestHandler;
        $this->responseEmitter = $responseEmitter;
        $this->eventManager = $eventManager;
    }

    public function run(ServerRequestInterface $request): void
    {
        $this->bootLoader->boot();
        $response = $this->requestHandler->handle($request);
        $this->responseEmitter->emit($response);
        $this->eventManager->trigger(new AppTerminating($request, $response));
        return;
    }
}
