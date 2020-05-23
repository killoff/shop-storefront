<?php

namespace Drinks\Storefront\RequestHandler;

use Drinks\Storefront\ServiceContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryRequestHandler implements RequestHandlerInterface
{
    /**
     * @var ServiceContainer
     */
    private $serviceContainer;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function canHandle(Request $request): bool
    {
        return $request->query->get('entity') === 'category';
    }

    public function handle(Request $request, Response $response): void
    {
        $params = [
            'index' => 'magento2_ch_de_catalog_product',
//            'type' => 'producdt',
            'body'  => [
                'from' => 0, 'size' => 50,
//                'query' => [
//                    'terms' => [
//                        'category_codes' => [(int)$request->query->get('entity_id')]
//                    ]
//                ]
            ]
        ];
//        echo json_encode($params);
        $hits = $this->serviceContainer->getElasticsearch()->search($params)['hits']['hits'];
        $products = array_column($hits, '_source');
        $twig = $this->serviceContainer->getTwig();
        $content = $twig->render(
            'category/view.twig',
            [
                'product' => [
                    'name' => 'Gin Mare'
                ],
                'products' => $products
            ]);
        $response->setContent($content);
        $response->send();
    }
}