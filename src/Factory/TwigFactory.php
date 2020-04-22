<?php

namespace Drinks\Storefront\Factory;

use Drinks\Storefront\Config;
use \Twig;

class TwigFactory
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
        $fallbackLoader = new \Twig\Loader\FilesystemLoader(STOREFRONT_DIR . '/templates/default');
        $storeLoader = new \Twig\Loader\FilesystemLoader(STOREFRONT_DIR . '/templates/b2b_drinks_ch');
        $loader = new \Twig\Loader\ChainLoader([$storeLoader, $fallbackLoader]);
        $twig = new \Twig\Environment($loader, [
//            'cache' => '/path/to/compilation_cache',
        ]);
        return $twig;
    }
}