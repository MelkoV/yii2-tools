<?php
return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => [
            'class'      => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1'],
            'generators' => [
                // generator name
                'giiant-model' => [
                    //generator class
                    'class'     => 'melkov\tools\gii\ModelGenerator',
                    //setting for out templates
                    'templates' => [
                        // template name => path to template
                        'melkov' =>
                            '@melkov/tools/gii/template',
                    ]
                ]
            ],
        ],
    ],
];
