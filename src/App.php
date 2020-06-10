<?php

namespace Drinks\Storefront;

use Drinks\Storefront\Exception\NoHandleFoundException;
use Drinks\Storefront\Exception\NoMatchFoundException;
use Drinks\Storefront\RequestHandler\RequestHandlersPool;
use Drinks\Storefront\Routing\RequestDecorator;
use Drinks\Storefront\Routing\RequestMatcher;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Stopwatch\Stopwatch;

class App
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Request
     */
    private $request;

    public function run(): void
    {
        $this->init();
        /** @var RequestHandlersPool $handlersPool */
        $handlersPool = $this->getContainer()->get(RequestHandlersPool::class);
        foreach ($handlersPool->getAll() as $handler) {
            if ($handler->canHandle($this->request)) {
                $response = $handler->handle($this->request);
                $this->dumpStopwatch($response);
                $response->send();
                break;
            }
        }
        throw new NoHandleFoundException('Request could not be handled.');
    }

    private function init(): void
    {
        if (!defined('STOREFRONT_DIR')) {
            define('STOREFRONT_DIR', dirname(__DIR__));
        }

        /** @var Stopwatch $stopwatch */
        $stopwatch = $this->getContainer()->get(Stopwatch::class);
        $stopwatch->start(__METHOD__, __METHOD__);

        $this->request = Request::createFromGlobals();
        /** @var RequestMatcher $matcher */
        $matcher = $this->getContainer()->get(RequestMatcher::class);
        $matchResult = $matcher->match($this->request);
        if ($matchResult === null) {
            throw new NoMatchFoundException('Request could not be matched.');
        }
        /** @var RequestDecorator $requestDecorator */
        $requestDecorator = $this->getContainer()->get(RequestDecorator::class);
        $requestDecorator->decorate($this->request, $matchResult);

        $stopwatch->stop(__METHOD__);
    }

    private function getContainer(): Container
    {
        if (null === $this->container) {
            $dotenv = new Dotenv();
            $dotenv->loadEnv(STOREFRONT_DIR . '/.env');

            if (!mkdir($concurrentDirectory = $_SERVER['DI_CACHE_PATH'], 0755, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
            $cachedContainerFile = $_SERVER['DI_CACHE_PATH'] .'/container.php';

            if (!file_exists($cachedContainerFile)) {
                $containerBuilder = new ContainerBuilder();
                $loader = new YamlFileLoader($containerBuilder, new FileLocator(STOREFRONT_DIR . '/config'));
                $loader->load('services.yaml');
                $containerBuilder->compile(true);

                $dumper = new PhpDumper($containerBuilder);
                file_put_contents(
                    $cachedContainerFile,
                    $dumper->dump([
                        'class' => 'CachedContainer',
                        'namespace' => 'Drinks\Storefront',
                    ])
                );
            }
            require_once $cachedContainerFile;
            $this->container = new CachedContainer();
        }
        return $this->container;
    }

    private function dumpStopwatch(Response $response): void
    {
        if (!$this->isDev()) {
            return;
        }

        /** @var Stopwatch $stopwatch */
        $stopwatch = $this->getContainer()->get(Stopwatch::class);
        $performanceData = [];
        foreach ($stopwatch->getSectionEvents('__root__') as $event) {
            $performanceData[$event->getCategory()] = $event->getDuration();
        }
        $response->setContent(
            $response->getContent() . '<pre>' . var_export($performanceData, true) . '</pre>'
        );
    }

    private function isDev(): bool
    {
        return isset($_SERVER['APP_ENV']) && ($_SERVER['APP_ENV'] === 'dev');
    }
}
