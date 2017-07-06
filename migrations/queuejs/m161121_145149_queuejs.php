<?php

// use yii\db\Schema;
use melkov\tools\console\Migration;

class m161121_145149_queuejs extends Migration
{
    public function getOperations()
    {
        return [];
//        return ["Controller:Action" => ["role1", "role2"]];
    }

    public function getNewTables()
    {
        return [];
    }

    public function getNewColumns()
    {
        return [];
    }

    public function safeUp()
    {
        
        $this->createTable("worker", [
            "id" => $this->primaryKey(),
            "name" => $this->string(),
            "slug" => $this->string()->notNull(),
            "auto_load" => $this->boolean()->notNull()->defaultValue(true)
        ]);
        $this->createIndex("worker_slug_idx", "worker", "slug", true);

        
    }

    public function safeDown()
    {
        $this->dropTables($this->getNewTables());
        $this->dropColumns($this->getNewColumns());
		$this->revokeOperationsAccesses();
    }

    /*
    // Use up/down to run migration code without a transaction
    public function up()
    {
    }

    public function down()
    {
    }
    */
}
