<?php

namespace melkov\tools\rbac;

class Controller extends \yii\web\Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => ControlFilter::className(),
            ]
        ]);
    }
}