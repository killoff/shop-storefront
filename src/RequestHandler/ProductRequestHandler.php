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
    private $serviceContainer;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function canHandle(Request $request): bool
    {
        return $request->query->get('entity') === 'product';
    }

    public function handle(Request $request, Response $response): void
    {
        $params = [
            'index' => 'products_drink_ch_de_ch',
            'id' => $request->query->get('entity_id'),
        ];

        $twig = $this->serviceContainer->getTwig();
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

    private function lookupEsIndex($entity, $store, $customerGroup, $locale)
    {
        $indexes = $this->serviceContainer->getConfig()->get('elasticsearch/indexes');
        return $indexes[$store]['product'][$customerGroup][$locale];
    }
}