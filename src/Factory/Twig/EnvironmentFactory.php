<?php

namespace Drinks\Storefront\Factory\Twig;

use Twig\Environment;
use Twig\Loader\LoaderInterface;

class EnvironmentFactory
{
    public function create(LoaderInterface $loader, $options = []): Environment
    {
        return new Environment($loader, $options);
    }
}
