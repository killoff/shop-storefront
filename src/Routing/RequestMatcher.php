<?php

namespace Drinks\Storefront\Routing;

use Drinks\Storefront\Factory\RedisFactory;
use Drinks\Storefront\Repository\WebsiteRepository;
use Symfony\Component\HttpFoundation\Request;

class RequestMatcher
{
    /**
     * @var RedisFactory
     */
    private $redisFactory;

    /**
     * @var WebsiteRepository
     */
    private $websiteRepository;

    public function __construct(RedisFactory $redisFactory, WebsiteRepository $websiteRepository)
    {
        $this->redisFactory = $redisFactory;
        $this->websiteRepository = $websiteRepository;
    }

    public function match(Request $request): ?string
    {
        $redisKey = sprintf(
            'url:%s:%s',
            $this->websiteRepository->getWebsiteByHost($request->getHost()),
            $request->getPathInfo()
        );
        return $this->redisFactory->create()->get($redisKey);
    }
}
