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
        $website = $this->getWebsiteByHost($request->getHost());
        $requestPath = $request->getPathInfo();
        $redisKey = "url:{$website}:{$requestPath}";
        $redis = $this->serviceContainer->getRedis();
        $value = $redis->get($redisKey);
        if ($value !== null) {
            $value = json_decode($value, true);
            $request->query->set('website', $website);
            $request->query->set('entity', $value['entity']);
            $request->query->set('entity_id', $value['entity_id']);
            $request->query->set('entity_locale', $value['locale']);
        }
    }

    private function getWebsiteByHost($host)
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
