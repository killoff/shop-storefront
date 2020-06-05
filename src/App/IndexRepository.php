<?php

namespace Drinks\Storefront\App;

use Drinks\Storefront\Repository\ElasticIndicesRepository;

class IndexRepository
{
    /**
     * @var ElasticIndicesRepository
     */
    private $elasticIndicesRepository;

    public function __construct(ElasticIndicesRepository $elasticIndicesRepository)
    {
        $this->elasticIndicesRepository = $elasticIndicesRepository;
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
        $indices = $this->elasticIndicesRepository->getAll();
        if (isset($indices[$website][$entity][$locale])) {
            return $indices[$website][$entity][$locale];
        }
        throw new \InvalidArgumentException("Index not found for '{$website}/{$entity}/{$locale}'");
    }
}
