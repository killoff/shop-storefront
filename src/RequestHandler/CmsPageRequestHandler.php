<?php

namespace Drinks\Storefront\RequestHandler;

use Drinks\Storefront\ServiceContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CmsPageRequestHandler implements RequestHandlerInterface
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
        // todo: if page not found => Elasticsearch\Common\Exceptions\Missing404Exception thrown.
        return $request->query->get('entity') === 'cms-page';
    }

    public function handle(Request $request, Response $response): void
    {
        $indexRepository = $this->serviceContainer->getIndexRepository();
        $website = $request->query->get('website');
        $locale = $request->query->get('locale');
        $esIndex = $indexRepository->lookupCmsPageIndex($website, $locale);
        $params = [
            'index' => $esIndex,
            'type' => 'page',
            'id'  => $request->query->get('entity_id')
        ];
        // todo: if page not found => Elasticsearch\Common\Exceptions\Missing404Exception thrown.
        $page = $this->serviceContainer->getElasticsearch()->get($params)['_source'];
        $twig = $this->serviceContainer->getTwig();
        $content = $twig->render(
            'cms/page.twig',
            [
                'page' => $page
            ]);
        $response->setContent($content);
        $response->send();
    }
}