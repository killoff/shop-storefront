<?php

namespace Drinks\Storefront\Factory;

use Drinks\Storefront\Factory\Symfony\Bridge\Twig\Extension\TranslationExtensionFactory;
use Drinks\Storefront\Factory\Twig\EnvironmentFactory;
use Drinks\Storefront\Factory\Twig\Loader\ChainLoaderFactory;
use Drinks\Storefront\Factory\Twig\Loader\FilesystemLoaderFactory;
use Drinks\Storefront\Repository\WebsiteRepository;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @var TranslatorFactory
     */
    private $translatorFactory;

    /**
     * @var TranslationExtensionFactory
     */
    private $translationExtensionFactory;

    /**
     * @var string
     */
    private $compileCachePath;

    public function __construct(
        FilesystemLoaderFactory $filesystemLoaderFactory,
        ChainLoaderFactory $chainLoaderFactory,
        EnvironmentFactory $environmentFactory,
        TranslatorFactory $translatorFactory,
        TranslationExtensionFactory $translationExtensionFactory,
        string $compileCachePath
    ) {
        $this->filesystemLoaderFactory = $filesystemLoaderFactory;
        $this->chainLoaderFactory = $chainLoaderFactory;
        $this->environmentFactory = $environmentFactory;
        $this->translatorFactory = $translatorFactory;
        $this->translationExtensionFactory = $translationExtensionFactory;
        $this->compileCachePath = $compileCachePath;
    }

    public function create(Request $request): Environment
    {
        $loaders = [];
        foreach ($request->query->get('twig_themes') as $theme) {
            $loaders[] = $this->filesystemLoaderFactory->create(STOREFRONT_DIR . '/templates/' . $theme);
        }
        $loaders[] = $this->filesystemLoaderFactory->create(STOREFRONT_DIR . '/templates/default');
        $loader = $this->chainLoaderFactory->create($loaders);
        $twigEnv = $this->environmentFactory->create($loader, ['cache' => $this->compileCachePath]);
        $translator = $this->translatorFactory->create($request->query->get('locale'));
        $twigEnv->addExtension($this->translationExtensionFactory->create($translator));
        return $twigEnv;
    }
}
