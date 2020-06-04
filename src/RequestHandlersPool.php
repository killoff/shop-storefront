<?php

namespace Drinks\Storefront;

class RequestHandlersPool
{
    /**
     * @var RequestHandlerInterface[]
     */
    private $handlerInterfaces;

    public function __construct(RequestHandlerInterface ...$handlerInterfaces)
    {
        $this->handlerInterfaces = $handlerInterfaces;
    }

    /**
     * @return RequestHandlerInterface[]
     */
    public function getAll(): array
    {
        return $this->handlerInterfaces;
    }
}
