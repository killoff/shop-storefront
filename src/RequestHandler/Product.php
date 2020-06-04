<?php

namespace Drinks\Storefront\RequestHandler;

use Drinks\Storefront\App\Config;
use Drinks\Storefront\Factory\Symfony\Component\HttpFoundation\ResponseFactory;
use Drinks\Storefront\Factory\TwigFactory;
use Drinks\Storefront\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class Product implements RequestHandlerInterface
{
    private const HANDLER_TYPE = 'product';

    /**
     * @var Config
     */
    private $config;

    /**
     * @var TwigFactory
     */
    private $twigFactory;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(Config $config, TwigFactory $twigFactory, ResponseFactory $responseFactory)
    {
        $this->config = $config;
        $this->twigFactory = $twigFactory;
        $this->responseFactory = $responseFactory;
    }

    public function canHandle(Request $request): bool
    {
        return $request->query->get('entity') === self::HANDLER_TYPE;
    }

    public function handle(Request $request): void
    {
//        $params = [
//            'index' => 'magento2_ch_de_catalog_product',
//            'id' => $request->query->get('entity_id'),
//            'type' => 'product',
//        ];
//
//        $product = $this->serviceContainer->getElasticsearch()->get($params);
//        print_r($product);
//        exit;

        $twig = $this->twigFactory->create();
        $content = $twig->render(
            'product/view.twig',
            [
                'product' => [
                    'name' => 'Gin Mare'
                ]
            ]);
        $response = $this->responseFactory->create();
        $response->setContent($content);
        $response->send();
    }

    private function lookupEsIndex($entity, $store, $customerGroup, $locale)
    {
        $indexes = $this->config->get('elasticsearch/indexes');
        return $indexes[$store]['product'][$customerGroup][$locale];
    }
}