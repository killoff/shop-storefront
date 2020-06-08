<?php

namespace Drinks\Storefront\Factory;

use Symfony\Component\Translation\Formatter\MessageFormatterInterface;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

class TranslatorFactory
{
    public function create(
        ?string $locale,
        MessageFormatterInterface $formatter = null,
        string $cacheDir = null,
        bool $debug = false,
        array $cacheVary = []
    ): Translator {
        $translator = new Translator($locale, $formatter, $cacheDir, $debug, $cacheVary);
        $translator->addLoader('yaml', new YamlFileLoader());
        $translator->addResource('yaml', STOREFRONT_DIR . '/translations/' . $locale . '.yaml', $locale);
        return $translator;
    }
}
