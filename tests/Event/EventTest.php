<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Event;

use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    /** @var string */
    private $name;

    /** @var \stdClass */
    private $target;

    /** @var array */
    private $params;

    /** @var Event */
    private $event;

    public function setUp()
    {
        parent::setUp();
        $this->name = 'event.name';
        $this->target = new \stdClass();
        $this->params = [
            'dummy' => 'params'
        ];
        $this->event = new Event($this->name, $this->target, $this->params);
    }

    /**
     * @inheritdoc
     */
    public function test_getName()
    {
        $this->assertEquals($this->name, $this->event->getName());
    }

    /**
     * @inheritdoc
     */
    public function test_getTarget()
    {
        $this->assertSame($this->target, $this->event->getTarget());
    }

    /**
     * @inheritdoc
     */
    public function test_getParams()
    {
        $this->assertEquals($this->params, $this->event->getParams());
    }

    /**
     * @inheritdoc
     */
    public function test_getParam()
    {
        $this->assertEquals($this->params['dummy'], $this->event->getParam('dummy'));
        $this->assertNull($this->event->getParam('not_exist'));
    }

    /**
     * @inheritdoc
     */
    public function test_setName()
    {
        $newName = 'new.event.name';
        $this->event->setName($newName);
        $this->assertEquals($newName, $this->event->getName());
    }

    /**
     * @inheritdoc
     */
    public function test_setName_throws_exception_when_object_given()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->event->setName(new \stdClass());
    }

    /**
     * @inheritdoc
     */
    public function test_setTarget()
    {
        $newTarget = 'new target';
        $this->event->setTarget($newTarget);
        $this->assertEquals($newTarget, $this->event->getTarget());
    }

    /**
     * @inheritdoc
     */
    public function test_setParams()
    {
        $newParams = [
            'new' => 'params'
        ];
        $this->event->setParams($newParams);
        $this->assertEquals($newParams, $this->event->getParams());
    }

    /**
     * @inheritdoc
     */
    public function test_stopPropagation()
    {
        $this->event->stopPropagation(true);
        $this->assertTrue($this->event->isPropagationStopped());
        $this->event->stopPropagation(false);
        $this->assertFalse($this->event->isPropagationStopped());
    }

    /**
     * @inheritdoc
     */
    public function test_stopPropagation_throws_exception_when_int_given()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->event->stopPropagation(1);
    }

    /**
     * @inheritdoc
     */
    public function test_isPropagationStopped()
    {
        $this->assertFalse($this->event->isPropagationStopped());
    }
}
