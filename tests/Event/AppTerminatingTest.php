<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Event;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;

class AppTerminatingTest extends TestCase
{
    /** @var ServerRequest */
    private $request;

    /** @var Response */
    private $response;

    /** @var AppTerminating */
    private $event;

    public function setUp()
    {
        parent::setUp();
        $this->request = new ServerRequest();
        $this->response = new Response();
        $this->event = new AppTerminating($this->request, $this->response);
    }
    /**
     * @inheritdoc
     */
    public function test_getName()
    {
        $this->assertEquals(AppTerminating::NAME, $this->event->getName());
    }

    /**
     * @inheritdoc
     */
    public function test_getTarget()
    {
        $this->assertNull($this->event->getTarget());
    }

    /**
     * @inheritdoc
     */
    public function test_getParams()
    {
        $this->assertEquals(['request' => $this->request, 'response' => $this->response], $this->event->getParams());
    }

    /**
     * @inheritdoc
     */
    public function test_getParam()
    {
        $this->assertSame($this->request, $this->event->getParam('request'));
        $this->assertSame($this->response, $this->event->getParam('response'));
    }
}
