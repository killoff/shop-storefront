<?php

namespace Drinks\Storefront\App;

use Drinks\Storefront\ServiceContainer;

class Customer
{
    /**
     * @var ServiceContainer
     */
    private $serviceContainer;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }
}
