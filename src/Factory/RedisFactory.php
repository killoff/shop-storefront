<?php

namespace Drinks\Storefront\Factory;

use Drinks\Storefront\Config;

class RedisFactory
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function create($database)
    {
        $options = [
            'parameters' => [
                'database' => $database
            ]
        ];
        return new \Predis\Client($this->config->get('redis/uri'), $options);
    }
}