<?php

namespace melkov\tools\db;

class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * @var array
     * Хранит измененные данные модели, доступные после save()
     */
    private $changedAttributesAfterSave = [];

    /**
     * return [
     *      "scenario" => [
     *          "allow" => [],
     *          "disallow" => [],
     *      ]
     * ];
     *
     * @return array
     */
    protected function loadAccess()
    {
        return [];
    }

    /**
     * @param $insert
     * @param $changedAttributes
     *
     * Сохраняет измененные аттрибуты в локальной переменной для доступа после save(), игнорируя проверку по типу.
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->changedAttributesAfterSave = [];
        foreach ($changedAttributes as $k => $v) {
            if ($v == $this->$k) {
                continue;
            }
            $this->changedAttributesAfterSave[$k] = $v;
        }
    }

    /**
     * @param $values
     * @param bool $safeOnly
     *
     * Заполняет модель данными с учетом политики loadAccess()
     */
    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
            $access = $this->loadAccess();
            $allow = $attributes;
            $scenario = $this->getScenario();
            if (isset($access[$scenario])) {
                if (isset($access[$scenario]["allow"])) {
                    $allow = array_flip($access[$scenario]["allow"]);
                }
                if (isset($access[$scenario]["disallow"])) {
                    foreach ($access[$scenario]["disallow"] as $dis) {
                        if (isset($allow[$dis])) {
                            unset($allow[$dis]);
                        }
                    }
                }
            }
            foreach ($values as $name => $value) {
                if (isset($attributes[$name]) && isset($allow[$name])) {
                    $this->$name = $value;
                } elseif ($safeOnly) {
                    $this->onUnsafeAttribute($name, $value);
                }
            }
        }
    }

    /**
     * @param $name
     * @return bool
     *
     * Изменен ли аттрибут. Доступен только после save()
     */
    public function isChangedAfterSave($name)
    {
        return array_key_exists($name, $this->changedAttributesAfterSave);
    }

    /**
     * @param $name
     * @return mixed|null
     *
     * Возвращает значение измененного аттрибута. Доступен только после save()
     */
    public function getChangedAttributeAfterSave($name)
    {
        return $this->isChanged($name) ? $this->changedAttributesAfterSave[$name] : null;
    }

    public function attributeHints()
    {
        return [];
    }

    public function getAttributeHint($name)
    {
        $hints = $this->attributeHints();
        if (isset($hints[$name])) {
            return $hints[$name];
        }
        return null;
    }

    /**
     * @return $this
     *
     * Подготавливает модель для передачи во view
     */
    public function setViewAttributes()
    {
        return $this;
    }
}