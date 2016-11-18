<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace melkov\models\base;

use Yii;

/**
 * This is the base-model class for table "geo_region".
 *
 * @property integer $id
 * @property string $name
 * @property integer $federal_district_id
 *
 * @property \common\models\GeoCity[] $geoCities
 * @property \common\models\GeoFederalDistrict $federalDistrict
 * @property string $aliasModel
 */
abstract class GeoRegion extends \melkov\db\ActiveRecord 
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geo_region';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'federal_district_id'], 'required'],
            [['federal_district_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['federal_district_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeoFederalDistrict::className(), 'targetAttribute' => ['federal_district_id' => 'id']],
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
            'federal_district_id' => Yii::t('label', 'Federal District ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoCities()
    {
        return $this->hasMany(\melkov\models\GeoCity::className(), ['region_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFederalDistrict()
    {
        return $this->hasOne(\melkov\models\GeoFederalDistrict::className(), ['id' => 'federal_district_id']);
    }


    
    /**
     * @inheritdoc
     * @return \common\models\query\GeoRegionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \melkov\models\query\GeoRegionQuery(get_called_class());
    }


}
