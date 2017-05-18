<?php

namespace melkov\gii;

use schmunk42\giiant\generators\model\Generator;
use schmunk42\giiant\helpers\SaveForm;
use Yii;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\StringHelper;

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
            array_unshift($rules, "[[" . implode(", ", $columns) . "], 'trim', 'skipOnEmpty' => true]");
        }
        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();

        foreach ($this->getTableNames() as $tableName) {
            list($relations, $translations) = array_values($this->extractTranslations($tableName, $relations));
//var_dump($relations,$tableName);exit;
            $className = php_sapi_name() === 'cli'
                ? $this->generateClassName($tableName)
                : $this->modelClass;

            $queryClassName = ($this->generateQuery) ? $this->generateQueryClassName($className) : false;
            $tableSchema = $db->getTableSchema($tableName);

            if ($this->tablePrefix && strpos($tableName, $this->tablePrefix) === 0) {
                $tableNameSh = substr($tableName, strlen($this->tablePrefix));
            } else {
                $tableNameSh = $tableName;
            }

            $params = [
                'tableName' => $tableName,
                'tableNameSh' => $tableNameSh,
                'className' => $className,
                'queryClassName' => $queryClassName,
                'tableSchema' => $tableSchema,
                'labels' => $this->generateLabels($tableSchema),
                'hints' => $this->generateHints($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => isset($relations[$tableName]) ? $relations[$tableName] : [],
                'ns' => $this->ns,
                'enum' => $this->getEnum($tableSchema->columns),
            ];

            if (!empty($translations)) {
                $params['translation'] = $translations;
            }

            $params['blameable'] = $this->generateBlameable($tableSchema);
            $params['timestamp'] = $this->generateTimestamp($tableSchema);

            $files[] = new CodeFile(
                Yii::getAlias(
                    '@'.str_replace('\\', '/', $this->ns)
                ).'/base/'.$className.$this->baseClassSuffix.'.php',
                $this->render('model.php', $params)
            );

            $modelClassFile = Yii::getAlias('@'.str_replace('\\', '/', $this->ns)).'/'.$className.'.php';
            if ($this->generateModelClass || !is_file($modelClassFile)) {
                $files[] = new CodeFile(
                    $modelClassFile,
                    $this->render('model-extended.php', $params)
                );
            }

            if ($queryClassName) {
                $queryClassFile = Yii::getAlias(
                        '@'.str_replace('\\', '/', $this->queryNs)
                    ).'/'.$queryClassName.'.php';
                if ($this->generateModelClass || !is_file($queryClassFile)) {
                    $params = [
                        'className' => $queryClassName,
                        'modelClassName' => $className,
                    ];
                    $files[] = new CodeFile(
                        $queryClassFile,
                        $this->render('query.php', $params)
                    );
                }
            }

            /*
             * create gii/[name]GiiantModel.json with actual form data
             */
            $suffix = str_replace(' ', '', $this->getName());
            $formDataDir = Yii::getAlias('@'.str_replace('\\', '/', $this->ns));
            $formDataFile = StringHelper::dirname($formDataDir)
                .'/gii'
                .'/'.$tableName.$suffix.'.json';

            $formData = json_encode(SaveForm::getFormAttributesValues($this, $this->formAttributes()));
            $files[] = new CodeFile($formDataFile, $formData);
        }

        return $files;
    }
}