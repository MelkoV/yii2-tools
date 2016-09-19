<?php

namespace melkov\components\controllers;

use melkov\filters\RbacControlFilter;

class RbacController extends WebController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => RbacControlFilter::className(),
            ]
        ]);
    }
}