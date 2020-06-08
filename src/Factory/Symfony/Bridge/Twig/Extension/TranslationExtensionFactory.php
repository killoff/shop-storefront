<?php

namespace Drinks\Storefront\Factory\Symfony\Bridge\Twig\Extension;

use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Twig\NodeVisitor\NodeVisitorInterface;

class TranslationExtensionFactory
{
    public function create(
        $translator = null,
        NodeVisitorInterface $translationNodeVisitor = null
    ): TranslationExtension {
        return new TranslationExtension($translator, $translationNodeVisitor);
    }
}
