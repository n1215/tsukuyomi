<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi;

use N1215\Http\Router\RouterInterface;
use N1215\Http\Router\RoutingErrorResponderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RoutingHandler implements RoutingHandlerInterface
{
    /** @var RouterInterface  */
    private $router;

    /** @var RoutingErrorResponderInterface  */
    private $errorResponder;

    public function __construct(RouterInterface $router, RoutingErrorResponderInterface $errorResponder)
    {
        $this->router = $router;
        $this->errorResponder = $errorResponder;
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $result = $this->router->match($request);

        if (!$result->isSuccess()) {
            return $this->errorResponder->respond($result->getError());
        }

        foreach ($result->getMatchedParams() as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        return $result->getMatchedHandler()->handle($request);
    }
}
