<?php

namespace melkov\tools\models;

use Yii;
use \melkov\tools\models\base\GeoRegion as BaseGeoRegion;

/**
 * This is the model class for table "geo_region".
 */
class GeoRegion extends BaseGeoRegion
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
