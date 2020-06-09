<?php

namespace Drinks\Storefront\Routing;

use Drinks\Storefront\Factory\RedisFactory;
use Drinks\Storefront\Repository\WebsiteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Stopwatch\Stopwatch;

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

    /**
     * @var Stopwatch
     */
    private $stopwatch;

    public function __construct(RedisFactory $redisFactory, WebsiteRepository $websiteRepository, Stopwatch $stopwatch)
    {
        $this->redisFactory = $redisFactory;
        $this->websiteRepository = $websiteRepository;
        $this->stopwatch = $stopwatch;
    }

    public function match(Request $request): ?string
    {
        $this->stopwatch->start(__METHOD__, __METHOD__);
        $redisKey = sprintf(
            'url:%s:%s',
            $this->websiteRepository->getWebsiteByHost($request->getHost()),
            $request->getPathInfo()
        );
        $value = $this->redisFactory->create()->get($redisKey);
        $this->stopwatch->stop(__METHOD__);
        return $value;
    }
}
