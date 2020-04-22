<?php

namespace Drinks\Storefront;

use Symfony\Component\HttpFoundation\Request;
use Drinks\Storefront\Config;

class RequestDecorator
{
    /**
     * @var ServiceContainer
     */
    private $serviceLocator;

    /**
     * @var \Drinks\Storefront\Config
     */
    private $config;

    public function __construct(ServiceContainer $serviceLocator, Config $config)
    {
        $this->serviceLocator = $serviceLocator;
        $this->config = $config;
    }

    public function decorate(Request $request)
    {
        $redis = $this->serviceLocator->get('redis/0');
//        $redis = $this->serviceLocator->get('redis');
//        $value = $redis->get($request->getRequestUri());
//        $value = json_decode($value, true);
//        $request->query->set('entity', $value['entity']);
//        $request->query->set('entity_id', $value['entity_id']);
        $request->query->set('entity', 'product');
        $request->query->set('entity_id', '123');
    }
}