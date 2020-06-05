<?php

namespace Drinks\Storefront\Factory;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticsearchFactory
{
    /**
     * @var string
     */
    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function create(): Client
    {
        return ClientBuilder::create()->setHosts([$this->url])->build();
    }
}
