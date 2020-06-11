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
        $websiteCode = $this->websiteRepository->getWebsiteByHost($request->getHost());
        $request->query->set('website', $websiteCode);
        $request->query->set('twig_themes', $this->websiteRepository->getWebsiteThemes($websiteCode));
        if (isset($decodedData['entity'])) {
            $request->query->set('request_type', $decodedData['entity']);
        }
        if (isset($decodedData['entity_id'])) {
            $request->query->set('entity_id', $decodedData['entity_id']);
        }
        if (isset($decodedData['locale'])) {
            $request->query->set('locale', $decodedData['locale']);
        }
    }
}
