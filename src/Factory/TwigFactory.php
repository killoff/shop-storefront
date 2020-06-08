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
     * @var WebsiteRepository
     */
    private $websiteRepository;

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
        WebsiteRepository $websiteRepository,
        string $compileCachePath
    ) {
        $this->filesystemLoaderFactory = $filesystemLoaderFactory;
        $this->chainLoaderFactory = $chainLoaderFactory;
        $this->environmentFactory = $environmentFactory;
        $this->translatorFactory = $translatorFactory;
        $this->translationExtensionFactory = $translationExtensionFactory;
        $this->compileCachePath = $compileCachePath;
        $this->websiteRepository = $websiteRepository;
    }

    public function create(Request $request): Environment
    {
        $loaders = [];
        foreach ($this->websiteRepository->getWebsiteThemes($request->query->get('website')) as $theme) {
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
