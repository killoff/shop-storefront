<?php

namespace Drinks\Storefront;

use Drinks\Storefront\RequestHandler\CategoryRequestHandler;
use Drinks\Storefront\RequestHandler\CmsRequestHandler;
use Drinks\Storefront\RequestHandler\ProductRequestHandler;
use Drinks\Storefront\RequestHandler\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App
{
    public function run(Request $request, Response $response)
    {
        if (!defined('STOREFRONT_DIR')) {
            throw new \RuntimeException('STOREFRONT_DIR not defined');
        }
        $serviceContainer = new ServiceContainer();
        (new RequestDecorator($serviceContainer))->decorate($request);
        $handlers = [
            ProductRequestHandler::class,
            CategoryRequestHandler::class,
            CmsRequestHandler::class,
        ];
        foreach ($handlers as $handlerClass) {
            /** @var RequestHandlerInterface $handler */
            $handler = new $handlerClass($serviceContainer);
            if ($handler->canHandle($request)) {
                $handler->handle($request, $response);
                break;
            }
        }
    }
}