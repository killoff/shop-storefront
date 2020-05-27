<?php

namespace Drinks\Storefront\App;

class IndexRepository
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function lookupProductIndex($website, $locale)
    {
        return $this->lookupIndex($website, 'product', $locale);
    }

    public function lookupCategoryIndex($website, $locale)
    {
        return $this->lookupIndex($website, 'category', $locale);
    }

    public function lookupCmsPageIndex($website, $locale)
    {
        return $this->lookupIndex($website, 'cms_page', $locale);
    }

    private function lookupIndex($website, $entity, $locale)
    {
        $indices = $this->config->get('elasticsearch/indices');
        if (isset($indices[$website][$entity][$locale])) {
            return $indices[$website][$entity][$locale];
        }
        throw new \InvalidArgumentException("Index not found for '{$website}/{$entity}/{$locale}'");
    }
}