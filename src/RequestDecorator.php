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
     * @var Config
     */
    private $config;

    public function __construct(ServiceContainer $serviceContainer, Config $config)
    {
        $this->serviceContainer = $serviceContainer;
        $this->config = $config;
    }

    public function decorate(Request $request): void
    {
        $storeCode = $this->getStoreCodeByHost($request->getHost());
        $requestUri = '/' . trim($request->getRequestUri(), '/');
        $requestPath = parse_url($requestUri, PHP_URL_PATH);
        $redisKey = "url:{$storeCode}:{$requestPath}";
        $redis = $this->serviceContainer->get('redis');
        $value = $redis->get($redisKey);
        if ($value !== null) {
            $value = json_decode($value, true);
            $request->query->set('entity', $value['entity']);
            $request->query->set('entity_id', $value['entity_id']);
            $request->query->set('entity_locale', $value['locale']);
        }
    }

    private function getStoreCodeByHost($host)
    {
        foreach ($this->config->get('stores') as $code => $store) {
            if (in_array($host, $store['hosts'])) {
                return $code;
            }
        }
        throw new \Exception("Store not found by host '{$host}'");
    }
}
