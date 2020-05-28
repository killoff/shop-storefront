<?php
return [
    'elasticsearch' => [
        'hosts' => [
            [
                'host' => 'localhost',
            ],
//            [
//                'host' => 'd01c1cc3cc16448bb09f8701163761ac.eu-central-1.aws.cloud.es.io',
//                'port' => '9243',
//                'scheme' => 'https',
//                'user' => 'elastic',
//                'pass' => '***'
//            ],

        ],

        'indices' => [
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
        ]
    ]
];