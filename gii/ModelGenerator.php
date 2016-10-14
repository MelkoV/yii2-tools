<?php

namespace melkov\gii;

use schmunk42\giiant\generators\model\Generator;
use yii\db\Schema;

class ModelGenerator extends Generator
{
    public $baseClass = 'melkov\db\ActiveRecord';

    public function generateRules($table)
    {
        $rules = parent::generateRules($table);
        $columns = [];
        foreach ($table->columns as $column) {
            if ($column->type == Schema::TYPE_STRING) {
                $columns[] = "'" . $column->name . "'";
            }
        }
        if ($columns) {
            $rules[] = "[[" . implode(", ", $columns) . "], 'trim']";
        }
        return $rules;
    }
}