<?php

namespace melkov\tools\models;

use Yii;
use \melkov\tools\models\base\GeoFederalDistrict as BaseGeoFederalDistrict;

/**
 * This is the model class for table "geo_federal_district".
 */
class GeoFederalDistrict extends BaseGeoFederalDistrict
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
