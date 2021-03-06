<?php

namespace melkov\tools\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * origin: http://yiiframework.ru/forum/viewtopic.php?t=19194&p=112881#p112881
     *
     * @param $array
     * @param $id
     * @param array $concattrs
     * @param string $separator
     * @return array
     */
    public static function cmap($array, $id, $concattrs=[], $separator=' '){
        $result = [];
        foreach ($array as $element) {
            $key = ArrayHelper::getValue($element, $id);
            $value=[];
            foreach($concattrs as $el){
                $value[] = ArrayHelper::getValue($element, $el);
            }
            $result[$key] = implode($separator, $value);
        }

        return $result;

    }

}