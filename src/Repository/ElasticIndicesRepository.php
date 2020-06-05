<?php

namespace Drinks\Storefront\Repository;

class ElasticIndicesRepository
{
    private $indices = [
        'drink_ch' => [
            'product' => [
                'de_CH' => 'magento2_de_catalog_product',
                'fr_CH' => 'magento2_fr_catalog_product',
            ],
            'category' => [
                'de_CH' => 'magento2_de_catalog_category',
                'fr_CH' => 'magento2_fr_catalog_category',
            ],
            'cms_page' => [
                'de_CH' => 'magento2_de_cms_page',
                'fr_CH' => 'magento2_fr_cms_page',
            ],
        ],
        'b2b_drink_ch' => [
            'product' => [
                'de_CH' => 'magento2_ch_de_catalog_product',
                'fr_CH' => 'magento2_ch_fr_catalog_product',
            ],
            'category' => [
                'de_CH' => 'magento2_ch_de_catalog_category',
                'fr_CH' => 'magento2_ch_fr_catalog_category',
            ],
        ],
        'b2c_drinks_de' => [
        ],
        'b2b_drinks_de' => [
        ],
    ];

    public function getAll(): array
    {
        return $this->indices;
    }
}
