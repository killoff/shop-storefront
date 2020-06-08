<?php

namespace Drinks\Storefront\RequestHandler;

use Drinks\Storefront\Factory\ElasticsearchFactory;
use Drinks\Storefront\Factory\Symfony\Component\HttpFoundation\ResponseFactory;
use Drinks\Storefront\Factory\TwigFactory;
use Drinks\Storefront\Repository\WebsiteRepository;
use Symfony\Component\HttpFoundation\Request;

class Cms implements RequestHandlerInterface
{
    private const HANDLER_TYPE = 'cms-page';

    /**
     * @var ElasticsearchFactory
     */
    private $elasticsearchFactory;

    /**
     * @var TwigFactory
     */
    private $twigFactory;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var WebsiteRepository
     */
    private $websiteRepository;

    public function __construct(
        ElasticsearchFactory $elasticsearchFactory,
        TwigFactory $twigFactory,
        ResponseFactory $responseFactory,
        WebsiteRepository $websiteRepository
    ) {
        $this->elasticsearchFactory = $elasticsearchFactory;
        $this->twigFactory = $twigFactory;
        $this->responseFactory = $responseFactory;
        $this->websiteRepository = $websiteRepository;
    }

    public function canHandle(Request $request): bool
    {
        // todo: if page not found => Elasticsearch\Common\Exceptions\Missing404Exception thrown.
        return $request->query->get('entity') === self::HANDLER_TYPE;
    }

    public function handle(Request $request): void
    {
        $website = $request->query->get('website');
        $locale = $request->query->get('locale');
        $esIndex = $this->websiteRepository->lookupCmsPageIndex($website, $locale);
        $params = [
            'index' => $esIndex,
            'type' => 'page',
            'id'  => $request->query->get('entity_id')
        ];
        // todo: if page not found => Elasticsearch\Common\Exceptions\Missing404Exception thrown.
        $page = $this->elasticsearchFactory->create()->get($params)['_source'];
        $twig = $this->twigFactory->create();
        $content = $twig->render(
            'cms/page.twig',
            [
                'page' => $page
            ]);
        $response = $this->responseFactory->create();
        $response->setContent($content);
        $response->send();
    }
}
