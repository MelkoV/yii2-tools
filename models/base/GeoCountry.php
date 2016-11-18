<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace melkov\models\base;

use Yii;

/**
 * This is the base-model class for table "geo_country".
 *
 * @property integer $id
 * @property string $name
 *
 * @property \common\models\GeoFederalDistrict[] $geoFederalDistricts
 * @property string $aliasModel
 */
abstract class GeoCountry extends \melkov\db\ActiveRecord 
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geo_country';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'trim']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'name' => Yii::t('label', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoFederalDistricts()
    {
        return $this->hasMany(\melkov\models\GeoFederalDistrict::className(), ['country_id' => 'id']);
    }


    
    /**
     * @inheritdoc
     * @return \common\models\query\GeoCountryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \melkov\models\query\GeoCountryQuery(get_called_class());
    }


}
