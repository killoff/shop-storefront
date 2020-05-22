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
        $websiteCode = $this->getWebsiteCodeByHost($request->getHost());
        $requestPath = $request->getPathInfo();
        $redisKey = "url:{$websiteCode}:{$requestPath}";
        $redis = $this->serviceContainer->getRedis();
        $value = $redis->get($redisKey);
        if ($value !== null) {
            $value = json_decode($value, true);
            $request->query->set('website_code', $websiteCode);
            $request->query->set('entity', $value['entity']);
            $request->query->set('entity_id', $value['entity_id']);
            $request->query->set('entity_locale', $value['locale']);
        }
        //to trigger ProductRequestHandler
         $request->query->set('entity', 'product');
         $request->query->set('entity_id', 20);
        $request->query->set('customer_sid', $request->cookies->get('PHPSESSID'));
    }

    private function getWebsiteCodeByHost($host)
    {
        $config = $this->serviceContainer->getConfig();
        foreach ($config->get('websites') as $code => $website) {
            if (in_array($host, $website['hosts'])) {
                return $code;
            }
        }
        throw new \Exception("Website not found by host '{$host}'");
    }
}
