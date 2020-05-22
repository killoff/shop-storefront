<?php
return [
    'stores' => [
        'drinks_ch' => [
            'description' => 'Drinks Switzerland',
            'hosts' => [
                'drinks.ch',
                'www.drinks.ch',
                'staging.drinks.ch',
                'drink.loc',
                'www.drink.loc',
                'magento2.loc',
                'www.magento2.loc',
                '127.0.0.1',
            ],
        ],
        'b2b_drinks_ch' => [
            'description' => 'Drinks Switzerland B2B',
            'hosts' => [
                'business.drinks.ch',
                'business.staging.drinks.ch',
                'business.drink.loc',
                'business.magento2.loc',
            ],
            'required_customer_groups' => [
                'handel',
                'gastro',
            ]
        ],
        'drinks_de' => [
            'description' => 'Drinks Germany',
            'hosts' => [
                'drinks.de',
                'www.drinks.de',
                'staging.drinks.de',
                'drink.deloc',
                'www.drink.deloc',
            ],
        ],
        'b2b_drinks_de' => [
            'description' => 'Drinks Germany B2B',
            'hosts' => [
                'business.drinks.de',
                'business.staging.drinks.de',
                'business.drink.deloc',
            ],
            'required_customer_groups' => [
                'handel',
                'gastro',
            ]
        ],
    ]
];