<?php
declare(strict_types=1);

namespace N1215\Tsukuyomi\Event;

class Event implements EventInterface
{
    /** @var string  */
    private $name;

    /** @var string|object|null */
    private $target;

    /** @var array  */
    private $params;

    /** @var bool  */
    private $isPropagationStopped;

    /**
     * @param string $name
     * @param string|object|null $target
     * @param array $params
     */
    public function __construct(string $name, $target = null, array $params = [])
    {
        $this->name = $name;
        $this->target = $target;
        $this->params = $params;
        $this->isPropagationStopped = false;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @inheritdoc
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @inheritdoc
     */
    public function getParam(string $name)
    {
        if (!isset($this->params[$name])) {
            return null;
        }

        return $this->params[$name];
    }

    /**
     * @inheritdoc
     */
    public function setName(string $name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name must be a string.');
        }

        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @inheritdoc
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @inheritdoc
     */
    public function stopPropagation(bool $flag)
    {
        if (!is_bool($flag)) {
            throw new \InvalidArgumentException('$flag must be a bool.');
        }

        $this->isPropagationStopped = $flag;
    }

    /**
     * @inheritdoc
     */
    public function isPropagationStopped(): bool
    {
        return $this->isPropagationStopped;
    }
}
