<?php

namespace Drinks\Storefront\RequestHandler;

use Drinks\Storefront\Factory\ElasticsearchFactory;
use Drinks\Storefront\Factory\Symfony\Component\HttpFoundation\ResponseFactory;
use Drinks\Storefront\Factory\TwigFactory;
use Drinks\Storefront\Repository\WebsiteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Stopwatch\Stopwatch;

class Category implements RequestHandlerInterface
{
    public const HANDLER_TYPE = 'category';

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

    /**
     * @var Stopwatch
     */
    private $stopwatch;

    public function __construct(
        ElasticsearchFactory $elasticsearchFactory,
        TwigFactory $twigFactory,
        ResponseFactory $responseFactory,
        WebsiteRepository $websiteRepository,
        Stopwatch $stopwatch
    ) {
        $this->elasticsearchFactory = $elasticsearchFactory;
        $this->twigFactory = $twigFactory;
        $this->responseFactory = $responseFactory;
        $this->websiteRepository = $websiteRepository;
        $this->stopwatch = $stopwatch;
    }

    public function canHandle(Request $request): bool
    {
        return $request->query->get('request_type') === self::HANDLER_TYPE;
    }

    public function handle(Request $request): Response
    {
        $this->stopwatch->start(__METHOD__, __METHOD__);
        $products = $this->getCategoryProducts($request);
        $content = $this->render($request, $products);
        $response = $this->responseFactory->create();
        $response->setContent($content);
        $this->stopwatch->stop(__METHOD__);
        return $response;
    }

    private function render(Request $request, array $products): string
    {
        $this->stopwatch->start(__METHOD__, __METHOD__);
        $twig = $this->twigFactory->create($request);
        $content = $twig->render(
            'category/view.twig',
            [
                'product' => [
                    'name' => 'Gin Mare'
                ],
                'products' => $products
            ]);

        $this->stopwatch->stop(__METHOD__);
        return $content;
    }

    private function getCategoryProducts(Request $request): array
    {
        $this->stopwatch->start(__METHOD__, __METHOD__);
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
        $this->stopwatch->stop(__METHOD__);
        return $products;
    }
}
