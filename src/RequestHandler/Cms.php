<?php

namespace Drinks\Storefront\RequestHandler;

use Drinks\Storefront\App\IndexRepository;
use Drinks\Storefront\Factory\ElasticsearchFactory;
use Drinks\Storefront\Factory\Symfony\Component\HttpFoundation\ResponseFactory;
use Drinks\Storefront\Factory\TwigFactory;
use Drinks\Storefront\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class Cms implements RequestHandlerInterface
{
    private const HANDLER_TYPE = 'cms-page';

    /**
     * @var IndexRepository
     */
    private $indexRepository;

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

    public function __construct(
        IndexRepository $indexRepository,
        ElasticsearchFactory $elasticsearchFactory,
        TwigFactory $twigFactory,
        ResponseFactory $responseFactory
    ) {
        $this->indexRepository = $indexRepository;
        $this->elasticsearchFactory = $elasticsearchFactory;
        $this->twigFactory = $twigFactory;
        $this->responseFactory = $responseFactory;
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
        $esIndex = $this->indexRepository->lookupCmsPageIndex($website, $locale);
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
