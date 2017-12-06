<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AppTerminating extends Event
{
    const NAME = 'app.terminating';

    /**
     * コンストラクタ
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        parent::__construct(self::NAME, null, ['request' => $request, 'response' => $response]);
    }
}
