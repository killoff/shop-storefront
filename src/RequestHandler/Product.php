<?php

namespace Drinks\Storefront\RequestHandler;

use Drinks\Storefront\Factory\Symfony\Component\HttpFoundation\ResponseFactory;
use Drinks\Storefront\Factory\TwigFactory;
use Drinks\Storefront\Repository\WebsiteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Product implements RequestHandlerInterface
{
    private const HANDLER_TYPE = 'product';

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
        TwigFactory $twigFactory,
        ResponseFactory $responseFactory,
        WebsiteRepository $websiteRepository
    ) {
        $this->twigFactory = $twigFactory;
        $this->responseFactory = $responseFactory;
        $this->websiteRepository = $websiteRepository;
    }

    public function canHandle(Request $request): bool
    {
        return $request->query->get('entity') === self::HANDLER_TYPE;
    }

    public function handle(Request $request): Response
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
        return $response;
    }
}
