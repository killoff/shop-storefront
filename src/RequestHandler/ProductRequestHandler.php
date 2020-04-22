<?php

namespace Drinks\Storefront\RequestHandler;

use Drinks\Storefront\ServiceContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductRequestHandler implements RequestHandlerInterface
{
    /**
     * @var ServiceContainer
     */
    private $serviceLocator;

    public function __construct(ServiceContainer $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function canHandle(Request $request)
    {
        return $request->query->get('entity') === 'product';
    }

    public function handle(Request $request, Response $response)
    {
        $params = [
            'index' => 'products_drink_ch_de_ch',
            'id' => $request->query->get('entity_id'),
        ];

        /** @var \Twig\Environment $twig */
        $twig = $this->serviceLocator->get('twig');
        $content = $twig->render(
            'product/view.twig',
            [
                'product' => [
                    'name' => 'Gin Mare'
                ]
            ]);
        $response->setContent($content);
        $response->send();
    }

}