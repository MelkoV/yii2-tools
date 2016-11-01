<?php

namespace melkov\db;

class ActiveRecord extends \yii\db\ActiveRecord
{
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
}