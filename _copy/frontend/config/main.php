<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
//            'class' => 'melkov\tools\LangUrlManager',
            'rules' => [
//                '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
                '/' => 'site/index',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'search' => 'site/search',
                'signup' => 'site/signup',
                'about' => 'site/about',
                'contacts' => 'site/contact',
                
                'image/<_size:[\w\_]+>/<_p0:[\w]+>/<_p1:[\w]+>/<_p2:[\w]+>/<_path:[\w]+>.<_ext:[\w]+>' => 'image/index',

//                '<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_c>/<_a>',
            ],
        ],
       
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'response' => [
            'formatters' => [
                'urlset' => 'common\components\UrlsetFormatter',
                'yml' => 'common\components\YmlFormatter',
            ],
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        YII_ENV_DEV ? 'css/bootstrap.css' :         'css/bootstrap.min.css',
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                    ]
                ]
            ],
        ],
    ],
    'params' => $params,
];
