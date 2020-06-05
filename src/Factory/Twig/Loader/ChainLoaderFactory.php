<?php

namespace Drinks\Storefront\Factory\Twig\Loader;

use Twig\Loader\ChainLoader;

class ChainLoaderFactory
{
    public function create(array $loaders = []): ChainLoader
    {
        return new ChainLoader($loaders);
    }
}
