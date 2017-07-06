<?php

namespace melkov\tools\models;

use Yii;
use \common\models\base\Worker as BaseWorker;

/**
 * This is the model class for table "worker".
 */
class Worker extends BaseWorker
{

    public function restart()
    {
//        Yii::$app->queueJs->deleteWorker($this->slug);
        Yii::$app->queueJs->addWorker($this->slug, "php " . dirname(Yii::getAlias("@app")) . "/yii queuejs/{name} {params}");
    }

}
