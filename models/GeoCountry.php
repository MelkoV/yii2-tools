<?php

namespace melkov\models;

use Yii;
use \melkov\models\base\GeoCountry as BaseGeoCountry;

/**
 * This is the model class for table "geo_country".
 */
class GeoCountry extends BaseGeoCountry
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