<?php

namespace melkov\components\rbac;

use melkov\components\App;
use Yii;
use yii\rbac\Rule;

/**
 * Checks if user group matches
 */
class UserRule extends Rule
{
    public $name = App::ROLE_USER;

    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            return true;
        }
        return false;
    }
}
