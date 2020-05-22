<?php

namespace Drinks\Storefront\Factory;

use Drinks\Storefront\App\Config;

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
        $websiteLoader = new \Twig\Loader\FilesystemLoader(STOREFRONT_DIR . '/templates/b2b_drinks_ch');
        $loader = new \Twig\Loader\ChainLoader([$websiteLoader, $fallbackLoader]);
        $twig = new \Twig\Environment($loader, [
//            'cache' => '/path/to/compilation_cache',
        ]);
        return $twig;
    }
}