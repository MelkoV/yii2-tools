<?php

namespace melkov\tools\models;

use Yii;
use \melkov\tools\models\base\GeoIp as BaseGeoIp;

/**
 * This is the model class for table "geo_ip".
 */
class GeoIp extends BaseGeoIp
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
