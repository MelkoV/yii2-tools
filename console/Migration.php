<?php

namespace melkov\tools\console;
use melkov\tools\App;

/**
 * Class Migration
 * @package melkov\tools\console
 *
 * todo rbac - to RbacManager
 * todo FK column
 * todo slug column
 */
class Migration extends \yii\db\Migration
{
    const TYPE_FOREIGN_KEY = "foreign_key";

    public $tableOptions = null;

    private $foreignKeys = [];

    public function getOperations()
    {
//        return ["Controller:Action" => ["role1", "role2"]];
        return [];
    }

    public function getNewTables()
    {
        return [];
    }

    public function getNewColumns()
    {
        return [];
    }

    public function depends()
    {

        return [];
    }

    public function init()
    {
        parent::init();
        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        foreach ($this->depends() as $depend) {
            echo "\nApply ".$depend."\n";
            exec(App::getPhpBin() . " yii migrate --interactive=0 --migrationPath=" . $depend, $out);
            foreach ($out as $line) {
                echo $line . "\n";
            }
        }
    }

    protected function foreignKey($table, $column, $notNull = false, $length = null, $unsigned = false)
    {
        return ["type" => self::TYPE_FOREIGN_KEY, "fk_table" => $table, "fk_column" => $column, "length" => $length, "not_null" => $notNull, "unsigned" => $unsigned];
    }

    protected function createTables($tables = [])
    {
        foreach ($tables as $tableName => $columns) {
            foreach ($columns as $name => &$column) {
                $this->makeColumn($tableName, $name, $column);

            }
            $this->createTable($tableName, $columns, $this->tableOptions);
        }

        $this->makeForeignKeys();

    }

    /**
     * @param array $columns
     *
     * [
     *      [table, column, type],
     *      [table, column, type],
     * ]
     */
    protected function addColumns($columns = [])
    {
        foreach ($columns as $c) {
            $type = $c[2];
            $this->makeColumn($c[0], $c[1], $type);
            $this->addColumn($c[0], $c[1], $type);
        }
        $this->makeForeignKeys();
    }

    protected function dropColumns($columns = [])
    {
        foreach ($columns as $c) {
            $this->dropColumn($c[0], $c[1]);
        }
    }

    protected function dropTables($tables = [])
    {
        $tables = array_keys($tables);
        $count = count($tables);
        for ($i = 0; $i < $count; $i++) {
            $this->dropTable(array_pop($tables));
        }
    }

    /**
     * @param array $operations
     */
    protected function createOperations($operations = [])
    {
        if (!$operations) {
            $operations = $this->getOperations();
        }

        foreach ($operations as $operation => $roles) {
            $this->createOperation($operation);
        }
    }

    /**
     * @param array $operations
     */
    protected function deleteOperations($operations = [])
    {
        if (!$operations) {
            $operations = $this->getOperations();
        }

        foreach ($operations as $operation => $roles) {
            $object = \Yii::$app->authManager->getPermission($operation);
            if ($object) {
                \Yii::$app->authManager->removeChildren($object);
                \Yii::$app->authManager->remove($object);
            }
        }
    }

    /**
     * @param array $operations
     */
    protected function addOperationsAccesses($operations = [])
    {
        if (!$operations) {
            $operations = $this->getOperations();
        }

        foreach ($operations as $operation => $roles) {
            $operObject = \Yii::$app->authManager->getPermission($operation);
            if (!$operObject) {
                $operObject = $this->createOperation($operation, isset($roles["description"]) ? $roles["description"] : "");
            }
            foreach ($roles as $role) {
                if (is_array($role)) {
                    continue;
                }

                $object = \Yii::$app->authManager->getRole($role);
                if (!$object) {
                    $object = $this->createRole($role);
                }
                if (!\Yii::$app->authManager->hasChild($object, $operObject)) {
                    \Yii::$app->authManager->addChild($object, $operObject);
                }
            }
        }
    }

    /**
     * @param array $operations
     */
    protected function revokeOperationsAccesses($operations = [])
    {
        if (!$operations) {
            $operations = $this->getOperations();
        }

        foreach ($operations as $oper => $roles) {
            $operation = \Yii::$app->authManager->getPermission($oper);
            if (!$operation) {
                continue;
            }
            foreach ($roles as $role) {
                if (is_array($role)) {
                    continue;
                }
                $object = \Yii::$app->authManager->getRole($role);
                if (!$object) {
                    continue;
                }
                \Yii::$app->authManager->removeChild($object, $operation);
            }
        }
    }

    /**
     * @param $role
     * @param $userId
     */
    protected function assign($role, $userId)
    {
        $obj = \Yii::$app->authManager->getRole($role);
        \Yii::$app->authManager->assign($obj, $userId);
    }

    /**
     * @param $name
     * @param $description
     * @return \yii\rbac\Permission
     */
    protected function createOperation($name, $description = "")
    {
        $object = \Yii::$app->authManager->createPermission($name);
        $object->description = $description;
        \Yii::$app->authManager->add($object);
        return $object;
    }

    /**
     * @param $name
     * @param \yii\rbac\Rule $rule
     * @param $description
     * @return \yii\rbac\Role
     */
    protected function createRole($name, $rule = null, $description = "")
    {
        $object = \Yii::$app->authManager->createRole($name);
        $object->description = $description;
        if ($rule) {
            $ruleObject = \Yii::$app->authManager->getRule($rule->name);
            if (!$ruleObject) {
                \Yii::$app->authManager->add($rule);
            }
            $object->ruleName = $rule->name;
        }
        \Yii::$app->authManager->add($object);
        return $object;
    }

    /**
     * @param $role
     */
    protected function deleteRole($role)
    {
        $object = \Yii::$app->authManager->getRole($role);
        if (!$object) {
            return;
        }
        \Yii::$app->authManager->remove($object);
    }

    protected function intNotNull($default = 0, $length = null)
    {
        $this->integer($length)->notNull()->defaultValue($default);
    }

    protected function dateNow($default = 'NOW()')
    {
        return $this->dateTime()->notNull()->defaultExpression($default);
    }

    protected function bTrue()
    {
        return $this->booleanNotNull(true);
    }

    protected function bFalse()
    {
        return $this->booleanNotNull(false);
    }

    protected function booleanNotNull($default = false)
    {
        return $this->boolean()->notNull()->defaultValue($default);
    }

    protected function slug($length = 20)
    {
        return $this->stringUnique($length);
    }

    protected function stringUnique($length = null, $notNull = true)
    {
        $column = $this->string($length)->unique();
        if ($notNull) {
            $column->notNull();
        }
        return $column;
    }

    /**
     * @param $tableName
     * @param $name
     * @param string|array $column column type
     */
    private function makeColumn($tableName, $name, &$column)
    {
        if (is_array($column) && isset($column["type"])) {
            if ($column["type"] == self::TYPE_FOREIGN_KEY) {
                $fkName = explode("_", $name);
                array_pop($fkName);
                $tableNameImp = strtr(\Yii::$app->db->schema->getRawTableName($tableName), ["`" => "", "'" => "", '"' => '']);
                array_unshift($fkName, $tableNameImp);
                $fkName[] = "fk";
                $this->foreignKeys[] = [implode("_", $fkName), $tableName, $name, $column["fk_table"], $column["fk_column"]];
                $notNull = $column["not_null"];
                $unsigned = isset($column["unsigned"]) ? $column["unsigned"] : false;
                $column = $this->integer($column["length"]);
                if ($notNull) {
                    $column->notNull();
                }
                if ($unsigned) {
                    $column->unsigned();
                }
            }
        }
    }

    private function makeForeignKeys()
    {
        foreach ($this->foreignKeys as $fk) {
            $this->addForeignKey($fk[0], $fk[1], $fk[2], $fk[3], $fk[4]);
        }
        $this->foreignKeys = [];
    }
}