<?php

namespace Drinks\Storefront\Factory;

use Predis\Client;

class RedisFactory
{
    public function create(): Client
    {
        return new Client('redis');
    }
}
