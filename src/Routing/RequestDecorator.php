<?php

namespace Drinks\Storefront\Routing;

use Drinks\Storefront\Repository\WebsiteRepository;
use Symfony\Component\HttpFoundation\Request;

class RequestDecorator
{
    /**
     * @var WebsiteRepository
     */
    private $websiteRepository;

    public function __construct(WebsiteRepository $websiteRepository)
    {
        $this->websiteRepository = $websiteRepository;
    }

    public function decorate(Request $request, string $jsonData): void
    {
        $decodedData = json_decode($jsonData, true);
        $request->query->set('website', $this->websiteRepository->getWebsiteByHost($request->getHost()));
        $request->query->set('entity', $decodedData['entity']);
        $request->query->set('entity_id', $decodedData['entity_id']);
        $request->query->set('locale', $decodedData['locale']);
    }
}
