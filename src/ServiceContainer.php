<?php

namespace Drinks\Storefront;

use Drinks\Storefront\App\Config;
use Drinks\Storefront\App\Customer;
use Drinks\Storefront\App\IndexRepository;
use Drinks\Storefront\Factory\ElasticsearchFactory;
use Drinks\Storefront\Factory\RedisFactory;
use Drinks\Storefront\Factory\TwigFactory;
use Predis\Client as RedisClient;
use Elasticsearch\Client as ElasticsearchClient;
use Twig\Environment as TwigEnvironment;

class ServiceContainer
{
    private $services = [];

    public function __construct()
    {
        AppDir::init();
    }

    public function getConfig(): Config
    {
        return $this->get('config');
    }

    public function getCustomer(): Customer
    {
        return $this->get('customer');
    }

    public function getIndexRepository(): IndexRepository
    {
        return $this->get('index_repository');
    }

    public function getRedis(): RedisClient
    {
        return $this->get('redis');
    }

    public function getElasticsearch(): ElasticsearchClient
    {
        return $this->get('elasticsearch');
    }

    public function getTwig(): TwigEnvironment
    {
        return $this->get('twig');
    }

    private function get($serviceName)
    {
        if (!$this->isUp($serviceName)) {
            $this->up($serviceName);
        }
        return $this->lookup($serviceName);
    }

    private function isUp($serviceName)
    {
        return isset($this->services[$serviceName]);
    }

    private function lookup($serviceName)
    {
        return $this->services[$serviceName] ?? null;
    }

    private function up($serviceName)
    {
        switch ($serviceName) {
            case 'config':
                $this->upConfig($serviceName);
                break;
            case 'customer':
                $this->upCustomer($serviceName);
                break;
            case 'index_repository':
                $this->upIndexRepository($serviceName);
                break;
            case 'redis':
            case 'redis/0':
                $this->upRedis('redis', 0);
                break;
            case 'redis/1':
                $this->upRedis($serviceName, 1);
                break;
            case 'elasticsearch':
                $this->upElasticsearch($serviceName);
                break;
            case 'twig':
                $this->upTwig($serviceName);
                break;
            default:
                throw new \InvalidArgumentException("Unable to locate service '{$serviceName}'");
        }

    }

    private function upConfig($serviceName)
    {
        $this->services[$serviceName] = new Config();
    }

    private function upCustomer($serviceName)
    {
        $this->services[$serviceName] = new Customer($this);
    }

    private function upIndexRepository($serviceName)
    {
        $this->services[$serviceName] = new IndexRepository($this->getConfig());
    }

    private function upTwig($serviceName)
    {
        $factory = new TwigFactory($this->get('config'));
        $this->services[$serviceName] = $factory->create();
    }

    private function upRedis($serviceName, $database)
    {
        $factory = new RedisFactory($this->get('config'));
        $this->services[$serviceName] = $factory->create($database);
    }

    private function upElasticsearch($serviceName)
    {
        $factory = new ElasticsearchFactory($this->get('config'));
        $this->services[$serviceName] = $factory->create();
    }
}