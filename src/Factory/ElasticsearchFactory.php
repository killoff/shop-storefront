<?php

namespace Drinks\Storefront\Factory;

use Drinks\Storefront\Config;
use Elasticsearch\ClientBuilder;

class ElasticsearchFactory
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function create()
    {
        $hosts = $this->config->get('elasticsearch/hosts');
        return ClientBuilder::create()->setHosts($hosts)->build();
    }
}