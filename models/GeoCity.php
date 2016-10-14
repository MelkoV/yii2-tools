<?php

namespace melkov\models;

use Yii;
use \melkov\models\base\GeoCity as BaseGeoCity;

/**
 * This is the model class for table "geo_city".
 */
class GeoCity extends BaseGeoCity
{

    public function rules()
    {
        return array_merge(parent::rules(), [

        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [

        ]);
    }

}
