<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

use Interop\Http\Server\RequestHandlerInterface;
use N1215\Tsukuyomi\Event\AppTerminating;
use N1215\Tsukuyomi\Event\EventManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class HttpApplicationTest extends TestCase
{
    public function test_run()
    {
        /** @var BootLoaderInterface|MockObject $bootLoader */
        $bootLoader = $this->createMock(BootLoaderInterface::class);
        $bootLoader
            ->expects($this->once())
            ->method('boot');


        $request = new ServerRequest();
        $response = new Response();

        /** @var RequestHandlerInterface|MockObject $requestHandler */
        $requestHandler = $this->createMock(RequestHandlerInterface::class);
        $requestHandler
            ->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturn($response);

        /** @var Response\EmitterInterface|MockObject $responseEmitter */
        $responseEmitter = $this->createMock(Response\EmitterInterface::class);
        $responseEmitter
            ->expects($this->once())
            ->method('emit')
            ->with($response);

        /** @var EventManagerInterface|MockObject $eventManager */
        $eventManager = $this->createMock(EventManagerInterface::class);
        $eventManager->expects($this->once())
            ->method('trigger')
            ->with($this->callback(function (AppTerminating $event) use ($request, $response) {
                return $request === $event->getParam('request')
                    && $response === $event->getParam('response');
            }));

        $httpApplication = new HttpApplication(
            $bootLoader,
            $requestHandler,
            $responseEmitter,
            $eventManager
        );

        $httpApplication->run($request);
    }
}
