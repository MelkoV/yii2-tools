<?php
return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => [
            'class'      => 'yii\gii\Module',
            'generators' => [
                // generator name
                'giiant-model' => [
                    //generator class
                    'class'     => 'melkov\gii\ModelGenerator',
                    //setting for out templates
                    'templates' => [
                        // template name => path to template
                        'melkov' =>
                            '@melkov/gii/template',
                    ]
                ]
            ],
        ],
    ],
];
