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

        'indexes' => [
            'drinks_ch' => [
                'product' => [
                    'privat' => [
                        'de_CH' => 'drinks_ch__products__privat__de_CH',
                        'de_FR' => 'drinks_ch__products__privat__de_FR'
                    ],
                    'guest' => [
                        'de_CH' => 'drinks_ch__products__guest__de_CH',
                        'de_FR' => 'drinks_ch__products__guest__de_FR'
                    ],
                ]
            ],
            'b2b_drinks_ch' => [
                'product' => [
                    'gastro' => [
                        'de_CH' => 'drinks_ch__products__gastro__de_CH',
                        'de_FR' => 'drinks_ch__products__gastro__de_FR'
                    ],
                    'handel' => [
                        'de_CH' => 'drinks_ch__products__handel__de_CH',
                        'de_FR' => 'drinks_ch__products__handel__de_FR'
                    ],
                ]
            ],
            'drinks_de' => [
                'product' => [
                    'privat' => [
                        'de_DE' => 'drinks_de__products__privat__de_CH',
                    ],
                    'guest' => [
                        'de_DE' => 'drinks_de__products__guest__de_CH',
                    ],
                ]
            ],
            'b2b_drinks_de' => [
                'product' => [
                    'gastro' => [
                        'de_DE' => 'drinks_de__products__gastro__de_CH',
                    ],
                    'handel' => [
                        'de_DE' => 'drinks_de__products__handel__de_CH',
                    ],
                ]
            ],
        ]
    ]
];