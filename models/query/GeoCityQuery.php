<?php

namespace melkov\tools\models\query;

/**
 * This is the ActiveQuery class for [[\melkov\tools\models\GeoCity]].
 *
 * @see \melkov\tools\models\GeoCity
 */
class GeoCityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \melkov\tools\models\GeoCity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \melkov\tools\models\GeoCity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
