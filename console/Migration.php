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

    /**
     * @param array $operations
     */
    public function createOperations($operations = [])
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
    public function deleteOperations($operations = [])
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
    public function addOperationsAccesses($operations = [])
    {
        if (!$operations) {
            $operations = $this->getOperations();
        }

        foreach ($operations as $operation => $roles) {
            $operObject = \Yii::$app->authManager->getPermission($operation);
            if (!$operObject) {
                $operObject = $this->createOperation($operation);
            }
            foreach ($roles as $role) {
                $object = \Yii::$app->authManager->getRole($role);
                if (!$object) {
                    $object = $this->createRole($role);
                }
                \Yii::$app->authManager->addChild($object, $operObject);
            }
        }
    }

    /**
     * @param array $operations
     */
    public function revokeOperationsAccesses($operations = [])
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
     */
    public function deleteRole($role)
    {
        $object = \Yii::$app->authManager->getRole($role);
        if (!$object) {
            return;
        }
        \Yii::$app->authManager->remove($object);
    }

    /**
     * @param $role
     * @param $userId
     */
    public function assign($role, $userId)
    {
        $obj = \Yii::$app->authManager->getRole($role);
        \Yii::$app->authManager->assign($obj, $userId);
    }

    public function foreignKey($table, $column, $notNull = false, $length = null, $unsigned = false)
    {
        return ["type" => self::TYPE_FOREIGN_KEY, "fk_table" => $table, "fk_column" => $column, "length" => $length, "not_null" => $notNull, $unsigned = $unsigned];
    }

    public function createTables($tables = [])
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
	public function addColumns($columns = [])
	{
        foreach ($columns as $c) {
            $type = $c[2];
            $this->makeColumn($c[0], $c[1], $type);
            $this->addColumn($c[0], $c[1], $type);
        }
        $this->makeForeignKeys();
	}
	
	public function dropColumns($columns = [])
	{
        foreach ($columns as $c) {
            $this->dropColumn($c[0], $c[1]);
        }
	}

    public function dropTables($tables = [])
    {
        $tables = array_keys($tables);
        $count = count($tables);
        for ($i = 0; $i < $count; $i++) {
            $this->dropTable(array_pop($tables));
        }
    }

    /** todo */
    public function updateRbac()
    {

    }

    /** todo */
    public function clearRbac()
    {

    }

    /**
     * @param $name
     * @return \yii\rbac\Permission
     */
    protected function createOperation($name)
    {
        $object = \Yii::$app->authManager->createPermission($name);
        \Yii::$app->authManager->add($object);
        return $object;
    }

    /**
     * @param $name
     * @param \yii\rbac\Rule $rule
     * @return \yii\rbac\Role
     */
    protected function createRole($name, $rule = null)
    {
        $object = \Yii::$app->authManager->createRole($name);
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
                $unsigned = $column["unsigned"];
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