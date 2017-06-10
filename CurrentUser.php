<?php

/**
 * Orig: https://github.com/carono/yii2-components/blob/master/CurrentUser.php
 */

namespace melkov;

use yii\base\Model;
use yii\helpers\Html;

class CurrentUser
{

    /**
     * Alias for \Yii::$app->user->id
     *
     * @return int|null|string
     */
    public static function getId()
    {
        return \Yii::$app->user->isGuest ? null : \Yii::$app->user->id;
    }

    /**
     * Alias for \Yii::$app->user->can()
     *
     * @param $operation
     * @return bool
     */
    public static function can($operation)
    {
        return \Yii::$app->user->can($operation);
    }

    /**
     * @param Model|string $message
     */
    public static function setFlashError($message)
    {
        if ($message instanceof Model) {
            $message = Html::errorSummary($message);
        }
        self::setFlash('error', $message);
    }

    /**
     * @param $message
     */
    public static function setFlashSuccess($message)
    {
        self::setFlash('success', $message);
    }

    /**
     * @param $message
     */
    public static function setFlashWarning($message)
    {
        self::setFlash('warning', $message);
    }

    /**
     * @param $message
     */
    public static function setFlashInfo($message)
    {
        self::setFlash('info', $message);
    }

    /**
     * @param null $key
     *
     * @return string
     */
    public static function showFlash($key = null)
    {
        $session = \Yii::$app->getSession();
        if (!$key) {
            $out = '';
            foreach ($session->getAllFlashes(false) as $key => $value) {
                $out .= self::showFlash($key);
            }
            return $out;
        } else {
            switch ($key) {
                case "success":
                    $htmlOptions = ["class" => "alert alert-success"];
                    break;
                case "error":
                    $htmlOptions = ["class" => "alert alert-danger"];
                    break;
                case "info":
                    $htmlOptions = ["class" => "alert alert-info"];
                    break;
                case "warning":
                    $htmlOptions = ["class" => "alert alert-warning"];
                    break;
                default:
                    $htmlOptions = ["class" => "alert alert-info"];
            }
            if ($session->hasFlash($key)) {
                return Html::tag('div', $session->getFlash($key), $htmlOptions);
            }
        };
    }

    /**
     * @param $name
     * @param $message
     */
    public static function setFlash($name, $message)
    {
        if (\Yii::$app->getSession()) {
            \Yii::$app->getSession()->setFlash($name, $message);
        }
    }
}