<?php

namespace melkov\tools;

class App
{
    const ROLE_GUEST = "guest";
    const ROLE_USER = "user";
    const ROLE_MANAGER = "manager";
    const ROLE_OPERATOR = "operator";
    const ROLE_ADMIN = "admin";


    /**
     * boolean filter for CRUD
     *
     * @return array
     */
    public static function booleanFilter()
    {
        return [true => \Yii::t("app", "Yes"), false => \Yii::t("app", "No")];
    }


    public static function getParam($key, $default = null)
    {
        return \yii\helpers\ArrayHelper::getValue(\Yii::$app->params, $key, $default);
    }

    public static function getPhpBin()
    {
        return self::getParam("phpBin", "php");
    }

}