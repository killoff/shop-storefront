<?php

namespace Drinks\Storefront;

use Drinks\Storefront\Exception\NoHandleFoundException;
use Drinks\Storefront\Exception\NoMatchFoundException;
use Drinks\Storefront\Routing\RequestDecorator;
use Drinks\Storefront\Routing\RequestMatcher;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;

class App
{
    public function run(): void
    {
        AppDir::init();

        $request = Request::createFromGlobals();
        /** @var RequestMatcher $matcher */
        $matcher = $this->getContainer()->get(RequestMatcher::class);
        $matchResult = $matcher->match($request);
        if ($matchResult === null) {
            throw new NoMatchFoundException('Request could not be matched.');
        }
        /** @var RequestDecorator $requestDecorator */
        $requestDecorator = $this->getContainer()->get(RequestDecorator::class);
        $requestDecorator->decorate($request, $matchResult);

        /** @var RequestHandlersPool $handlersPool */
        $handlersPool = $this->getContainer()->get(RequestHandlersPool::class);
        foreach ($handlersPool->getAll() as $handler) {
            if ($handler->canHandle($request)) {
                $handler->handle($request);
                break;
            }
        }
        throw new NoHandleFoundException('Request could not be handled.');
    }

    private function getContainer(): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();
        $loader = new YamlFileLoader($containerBuilder, new FileLocator(dirname(__DIR__) . '/config'));
        $loader->load('services.yaml');
        $containerBuilder->compile();
        return $containerBuilder;
    }
}
