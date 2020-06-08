<?php

namespace Drinks\Storefront\RequestHandler;

use Drinks\Storefront\Factory\ElasticsearchFactory;
use Drinks\Storefront\Factory\Symfony\Component\HttpFoundation\ResponseFactory;
use Drinks\Storefront\Factory\TwigFactory;
use Drinks\Storefront\Repository\WebsiteRepository;
use Drinks\Storefront\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class Category implements RequestHandlerInterface
{
    private const HANDLER_TYPE = 'category';

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
        return $request->query->get('entity') === self::HANDLER_TYPE;
    }

    public function handle(Request $request): void
    {
        $website = $request->query->get('website');
        $locale = $request->query->get('locale');
        $productIndex = $this->websiteRepository->lookupProductIndex($website, $locale);
        $categoryIndex = $this->websiteRepository->lookupCategoryIndex($website, $locale);
        $params = [
            'index' => $productIndex,
            'type' => 'product',
            'body'  => [
                'from' => 0, 'size' => 50,
                'query' => [
                    'terms' => [
                        'category_codes' => [(int)$request->query->get('entity_id')]
                    ]
                ]
            ]
        ];

        $hits = $this->elasticsearchFactory->create()->search($params)['hits']['hits'];
        $products = array_column($hits, '_source');
        $twig = $this->twigFactory->create();
        $content = $twig->render(
            'category/view.twig',
            [
                'product' => [
                    'name' => 'Gin Mare'
                ],
                'products' => $products
            ]);
        $response = $this->responseFactory->create();
        $response->setContent($content);
        $response->send();
    }
}
