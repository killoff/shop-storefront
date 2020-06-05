<?php

namespace Drinks\Storefront\Factory\Twig\Loader;

use Twig\Loader\FilesystemLoader;

class FilesystemLoaderFactory
{
    public function create($paths = [], string $rootPath = null): FilesystemLoader
    {
        return new FilesystemLoader($paths, $rootPath);
    }
}
