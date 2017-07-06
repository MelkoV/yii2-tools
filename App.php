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

    public static function getUrl($url, $protocol = "http", $strict = false)
    {
        $url = trim($url);
        $data = explode("://", $url);
        if (isset($data[1])) {
            if ($strict && $data[0] != $protocol) {
                return $protocol . "://" . $data[1];
            }
            return $url;
        }
        return $protocol . "://" . $url;
    }
}