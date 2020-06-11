<?php

namespace Drinks\Storefront\RequestHandler;

use Drinks\Storefront\Factory\Symfony\Component\HttpFoundation\ResponseFactory;
use Drinks\Storefront\Factory\TwigFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Stopwatch\Stopwatch;

class Search implements RequestHandlerInterface
{
    public const HANDLER_TYPE = 'search';

    /**
     * @var TwigFactory
     */
    private $twigFactory;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var Stopwatch
     */
    private $stopwatch;

    public function __construct(
        TwigFactory $twigFactory,
        ResponseFactory $responseFactory,
        Stopwatch $stopwatch
    ) {
        $this->twigFactory = $twigFactory;
        $this->responseFactory = $responseFactory;
        $this->stopwatch = $stopwatch;
    }

    public function canHandle(Request $request): bool
    {
        return $request->query->get('request_type') === self::HANDLER_TYPE;
    }

    public function handle(Request $request): Response
    {
        $this->stopwatch->start(__METHOD__, __METHOD__);
//        $twig = $this->twigFactory->create($request);
//        $content = $twig->render('search/page.twig');
        $response = $this->responseFactory->create();
//        $response->setContent($content);
        $this->stopwatch->stop(__METHOD__);
        return $response;
    }
}
