<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace melkov\tools\models\base;

use Yii;

/**
 * This is the base-model class for table "geo_federal_district".
 *
 * @property integer $id
 * @property string $name
 * @property integer $country_id
 *
 * @property \melkov\tools\models\GeoCountry $country
 * @property \melkov\tools\models\GeoRegion[] $geoRegions
 * @property string $aliasModel
 */
abstract class GeoFederalDistrict extends \melkov\tools\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geo_federal_district';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'country_id'], 'required'],
            [['country_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
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
            'country_id' => Yii::t('label', 'Country ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(\melkov\tools\models\GeoCountry::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoRegions()
    {
        return $this->hasMany(\melkov\tools\models\GeoRegion::className(), ['federal_district_id' => 'id']);
    }


    
    /**
     * @inheritdoc
     * @return \melkov\tools\models\query\GeoFederalDistrictQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \melkov\tools\models\query\GeoFederalDistrictQuery(get_called_class());
    }


}
