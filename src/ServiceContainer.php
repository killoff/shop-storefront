<?php

namespace Drinks\Storefront;

use Drinks\Storefront\Factory\ElasticsearchFactory;
use Drinks\Storefront\Factory\RedisFactory;
use Drinks\Storefront\Factory\TwigFactory;

class ServiceContainer
{
    private $services = [];

    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function get($serviceName)
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

    private function upTwig($serviceName)
    {
        $factory = new TwigFactory($this->config);
        $this->services[$serviceName] = $factory->create();
    }

    private function upRedis($serviceName, $database)
    {
        $factory = new RedisFactory($this->config);
        $this->services[$serviceName] = $factory->create($database);
    }

    private function upElasticsearch($serviceName)
    {
        $factory = new ElasticsearchFactory($this->config);
        $this->services[$serviceName] = $factory->create();
    }
}