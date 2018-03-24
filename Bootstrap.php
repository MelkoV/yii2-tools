<?php
namespace melkov\tools;
use yii\base\Application;
use yii\base\BootstrapInterface;
/**
 * Class Bootstrap
 *
 * @package melkov\tools
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

        \Yii::setAlias('@melkov', '@vendor/melkov');
        \Yii::setAlias('@melkov/tools', '@vendor/melkov/yii2-tools');

    }
}