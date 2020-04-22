<?php
return [
    'drinks_ch_de' => [
        'host' => 'www.drinks.ch',
        'locale' => 'de_CH',
        'required_customer_groups' => [
            'handel',
            'gastro',
        ]

    ],

    'store' => [

        // Switzerland
        'www.drinks.ch' => [
            'de' => [
                'code' => 'drinks_ch_de',
            ],

            'fr' => [
                'code' => 'drinks_ch_fr',
            ],
        ],
        'business.drinks.ch' => [
            'de' => [
                'code' => 'b2b_drinks_ch_de',
                'required_customer_groups' => [
                    'handel',
                    'gastro',
                ]
            ],
            'fr' => [
                'code' => 'b2b_drinks_ch_fr',
                'required_customer_groups' => [
                    'handel',
                    'gastro',
                ]
            ],
            'en' => '@de'
        ],

        // Germany
        'www.drinks.de' => [
            'de' => [
                'code' => 'drinks_de_de',
            ],
        ],
        'business.drinks.de' => [
            'de' => [
                'code' => 'b2b_drinks_de_de',
                'required_customer_groups' => [
                    'handel',
                    'gastro',
                ]
            ],
        ],

        // Staging
        'www.drink.loc' => [
            'de' => [
                'code' => 'drinks_ch_de',
            ],
            'fr' => [
                'code' => 'drinks_ch_fr',
            ],
        ],
        'business.drink.loc' => [
            'de' => [
                'code' => 'drinks_ch_de',
                'required_customer_groups' => [
                    'handel',
                    'gastro',
                ]
            ],
            'fr' => [
                'code' => 'drinks_ch_fr',
                'required_customer_groups' => [
                    'handel',
                    'gastro',
                ]
            ],
        ],
//        'www.drinks.deloc' => $de_b2c,
//        'business.drinks.deloc' => $de_b2b,
//
//        'staging.drinks.ch' => $ch_b2c,
//        'business.staging.drinks.ch' => $ch_b2b,
//        'staging.drinks.de' => $de_b2c,
//        'business.staging.drinks.de' => $de_b2b,
//
//        'germany.drinks.de' => $de_b2c,
//        'business.germany.drinks.de' => $de_b2b,
    ]

];