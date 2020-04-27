<?php

namespace Drinks\Storefront;

use Symfony\Component\HttpFoundation\Request;

class RequestDecorator
{
    /**
     * @var ServiceContainer
     */
    private $serviceContainer;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function decorate(Request $request): void
    {
        $storeCode = $this->getStoreCodeByHost($request->getHost());
        $requestPath = $request->getPathInfo();
        $redisKey = "url:{$storeCode}:{$requestPath}";
        $redis = $this->serviceContainer->getRedis();
        $value = $redis->get($redisKey);
        if ($value !== null) {
            $value = json_decode($value, true);
            $request->query->set('store', $storeCode);
            $request->query->set('entity', $value['entity']);
            $request->query->set('entity_id', $value['entity_id']);
            $request->query->set('entity_locale', $value['locale']);
        }
        //to trigger ProductRequestHandler $request->query->set('entity', 'product');
        $request->query->set('customer_sid', $request->cookies->get('PHPSESSID'));
    }

    private function getStoreCodeByHost($host)
    {
        $config = $this->serviceContainer->getConfig();
        foreach ($config->get('stores') as $code => $store) {
            if (in_array($host, $store['hosts'])) {
                return $code;
            }
        }
        throw new \Exception("Store not found by host '{$host}'");
    }
}
