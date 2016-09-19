<?php
namespace melkov\components;
use yii\base\Application;
use yii\base\BootstrapInterface;
/**
 * Class Bootstrap
 *
 * @package carono\components
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        /**
         * @var Module $gii
         */
        \Yii::setAlias('@melkov', '@vendor/melkov/yii2-tools');

    }
}