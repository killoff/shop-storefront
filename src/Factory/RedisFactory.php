<?php

namespace Drinks\Storefront\Factory;

use Predis\Client;

class RedisFactory
{
    /**
     * @var string
     */
    private $uri;

    public function __construct(string $uri)
    {
        $this->uri = $uri;
    }

    public function create(): Client
    {
        return new Client($this->uri);
    }
}
