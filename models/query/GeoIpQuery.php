<?php

namespace melkov\tools\models\query;

/**
 * This is the ActiveQuery class for [[\melkov\tools\models\GeoIp]].
 *
 * @see \melkov\tools\models\GeoIp
 */
class GeoIpQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \melkov\tools\models\GeoIp[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \melkov\tools\models\GeoIp|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
