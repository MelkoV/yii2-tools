<?php

namespace melkov\models\query;

/**
 * This is the ActiveQuery class for [[\melkov\models\GeoFederalDistrict]].
 *
 * @see \melkov\models\GeoFederalDistrict
 */
class GeoFederalDistrictQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \melkov\models\GeoFederalDistrict[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \melkov\models\GeoFederalDistrict|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
