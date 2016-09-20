<?php

namespace melkov\components\queuejs\lib;

use melkov\components\helpers\StringHelper;
use yii\console\Controller;

class QueueJsBaseCommand extends Controller
{
    public function init()
    {
        var_dump("init");
    }

    public function beforeAction($action)
    {
        // your custom code here, if you want the code to run before action filters,
        // wich are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl
var_dump($action);
        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true; // or false to not run the action
    }

    public function run($route, $args = array())
    {
        var_dump($route);
        var_dump($args);
        if (!isset($args[0])) {
            throw new QueueJsException(\Yii::t('queueJs', 'Command action not set'));
        }

        $action = trim($args[0]);

        if (\Yii::$app->queueJs->prefix) {
            $action = StringHelper::sub($action, StringHelper::len(\Yii::$app->queueJs->prefix));
        }

        if (!method_exists($this, $action)) {
            throw new QueueJsException(\Yii::t('queueJs', 'Command action "{action}" not found', array('{action}' => $action)));
        }

        $params = array();

        if (isset($args[1])) {
            $params = json_decode(urldecode($args[1]), true);
        }
        $this->$action($params);
    }
}