<?php

namespace melkov\components\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\LanguageSource]].
 *
 * @see \common\models\LanguageSource
 */
class LanguageSourceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \melkov\components\models\LanguageSource[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \melkov\components\models\LanguageSource|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}