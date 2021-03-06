<?php

namespace Drinks\Storefront\Repository;

class WebsiteRepository
{
    private const ENTITY_CODE_CATEGORY = 'category';

    private const ENTITY_CODE_CMS = 'cms_page';

    private const ENTITY_CODE_PRODUCT = 'product';

    private $config = [
        'drink_ch' => [
            'description' => 'Drinks Switzerland',
            'themes' => ['drinks-ch-b2c'],
            'hosts' => [
                'drinks.ch',
                'www.drinks.ch',
                'staging.drinks.ch',
                'drink.loc',
                'www.drink.loc',
                'magento2.loc',
                'www.magento2.loc',
                '127.0.0.1',
                'dev.drinks.ch',
            ],
            'indices' => [
                self::ENTITY_CODE_PRODUCT => [
                    'de_CH' => 'magento2_de_catalog_product',
                    'fr_CH' => 'magento2_fr_catalog_product',
                ],
                self::ENTITY_CODE_CATEGORY => [
                    'de_CH' => 'magento2_de_catalog_category',
                    'fr_CH' => 'magento2_fr_catalog_category',
                ],
                self::ENTITY_CODE_CMS => [
                    'de_CH' => 'magento2_de_cms_page',
                    'fr_CH' => 'magento2_fr_cms_page',
                ],
            ],
        ],
        'b2b_drink_ch' => [
            'description' => 'Drinks Switzerland B2B',
            'themes' => ['drinks-ch-b2b', 'drinks-ch-b2c'],
            'hosts' => [
                'business.drinks.ch',
                'business.staging.drinks.ch',
                'business.drink.loc',
                'business.magento2.loc',
            ],
            'indices' => [
                self::ENTITY_CODE_PRODUCT => [
                    'de_CH' => 'magento2_ch_de_catalog_product',
                    'fr_CH' => 'magento2_ch_fr_catalog_product',
                ],
                self::ENTITY_CODE_CATEGORY => [
                    'de_CH' => 'magento2_ch_de_catalog_category',
                    'fr_CH' => 'magento2_ch_fr_catalog_category',
                ],
            ],
            'required_customer_groups' => [
                'handel',
                'gastro',
            ]
        ],
        'b2c_drinks_de' => [
            'description' => 'Drinks Germany',
            'themes' => ['drinks-de-b2c'],
            'hosts' => [
                'drinks.de',
                'www.drinks.de',
                'staging.drinks.de',
                'drink.deloc',
                'www.drink.deloc',
            ],
            'indices' => [],
        ],
        'b2b_drinks_de' => [
            'description' => 'Drinks Germany B2B',
            'themes' => ['drinks-de-b2b'],
            'hosts' => [
                'business.drinks.de',
                'business.staging.drinks.de',
                'business.drink.deloc',
            ],
            'indices' => [],
            'required_customer_groups' => [
                'handel',
                'gastro',
            ]
        ],
    ];

    public function getAll(): array
    {
        return $this->config;
    }

    public function getWebsiteThemes(string $websiteCode): array
    {
        return $this->config[$websiteCode]['themes'];
    }

    public function getWebsiteByHost($host): string
    {
        foreach ($this->config as $code => $website) {
            if (in_array($host, $website['hosts'], true)) {
                return $code;
            }
        }
        throw new \RuntimeException(sprintf('Website not found by host [%s].', $host));
    }

    public function lookupProductIndex(string $website, string $locale)
    {
        return $this->lookupIndex($website, self::ENTITY_CODE_PRODUCT, $locale);
    }

    public function lookupCategoryIndex(string $website, string $locale)
    {
        return $this->lookupIndex($website, self::ENTITY_CODE_CATEGORY, $locale);
    }

    public function lookupCmsPageIndex(string $website, string $locale)
    {
        return $this->lookupIndex($website, self::ENTITY_CODE_CMS, $locale);
    }

    private function lookupIndex(string $website, string $entity, string $locale)
    {
        if (isset($this->config[$website]['indices'][$entity][$locale])) {
            return $this->config[$website]['indices'][$entity][$locale];
        }
        throw new \InvalidArgumentException("Index not found for '{$website}/{$entity}/{$locale}'");
    }
}
