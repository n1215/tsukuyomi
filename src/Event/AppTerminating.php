<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AppTerminating extends Event
{
    /**
     * コンストラクタ
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        parent::__construct('app.terminating', null, ['request' => $request, 'response' => $response]);
    }
}
