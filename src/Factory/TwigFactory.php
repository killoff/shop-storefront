<?php

namespace Drinks\Storefront\Factory;

use Drinks\Storefront\Factory\Twig\EnvironmentFactory;
use Drinks\Storefront\Factory\Twig\Loader\ChainLoaderFactory;
use Drinks\Storefront\Factory\Twig\Loader\FilesystemLoaderFactory;
use Twig\Environment;

class TwigFactory
{
    /**
     * @var FilesystemLoaderFactory
     */
    private $filesystemLoaderFactory;

    /**
     * @var ChainLoaderFactory
     */
    private $chainLoaderFactory;

    /**
     * @var EnvironmentFactory
     */
    private $environmentFactory;

    public function __construct(
        FilesystemLoaderFactory $filesystemLoaderFactory,
        ChainLoaderFactory $chainLoaderFactory,
        EnvironmentFactory $environmentFactory
    ) {
        $this->filesystemLoaderFactory = $filesystemLoaderFactory;
        $this->chainLoaderFactory = $chainLoaderFactory;
        $this->environmentFactory = $environmentFactory;
    }

    public function create(): Environment
    {
        $loader = $this->chainLoaderFactory->create([
            $this->filesystemLoaderFactory->create(STOREFRONT_DIR . '/templates/b2b_drinks_ch'),
            $this->filesystemLoaderFactory->create(STOREFRONT_DIR . '/templates/default')
        ]);
        return $this->environmentFactory->create($loader, [
//            'cache' => '/path/to/compilation_cache',
        ]);
    }
}
