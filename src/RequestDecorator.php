<?php

namespace Drinks\Storefront;

use Symfony\Component\HttpFoundation\Request;
use Drinks\Storefront\Config;

class RequestDecorator
{
    /**
     * @var ServiceContainer
     */
    private $serviceContainer;

    /**
     * @var \Drinks\Storefront\Config
     */
    private $config;

    public function __construct(ServiceContainer $serviceContainer, Config $config)
    {
        $this->serviceContainer = $serviceContainer;
        $this->config = $config;
    }

    public function decorate(Request $request): void
    {
        $redis = $this->serviceContainer->get('redis/0');
//        $redis = $this->serviceContainer->get('redis');
//        $value = $redis->get($request->getRequestUri());
//        $value = json_decode($value, true);
//        $request->query->set('entity', $value['entity']);
//        $request->query->set('entity_id', $value['entity_id']);
        $request->query->set('entity', 'product');
        $request->query->set('entity_id', '123');
    }
}