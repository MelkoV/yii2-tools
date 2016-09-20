<?php

namespace melkov\components\queuejs\lib;

use melkov\components\helpers\StringHelper;
use yii\console\Controller;
use yii\helpers\Json;

class QueueJsBaseCommand extends Controller
{


    public function beforeAction($action)
    {
        // your custom code here, if you want the code to run before action filters,
        // wich are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true; // or false to not run the action
    }

    public function runAction($id, $params = [])
    {
        if (\Yii::$app->queueJs->prefix) {
            $id = StringHelper::sub($id, StringHelper::len(\Yii::$app->queueJs->prefix));
        }
        if (isset($params[0])) {
            $params = Json::decode($params[0]);
        }
        return parent::runAction($id, $params);
    }


}