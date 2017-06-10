<?php

namespace melkov;

class App
{
    const ROLE_GUEST = "guest";
    const ROLE_USER = "user";
    const ROLE_MANAGER = "manager";
    const ROLE_OPERATOR = "operator";
    const ROLE_ADMIN = "admin";
    
    

    public static function booleanFilter()
    {
        return [true => \Yii::t("app", "Yes"), false => \Yii::t("app", "No")];
    }

    public static function getUrl($url)
    {
        $url = trim($url);
        if (strpos($url, "http://") === 0 || strpos($url, "https://") === 0) {
            return $url;
        }
        return "http://" . $url;
    }
}