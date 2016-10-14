<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */

echo "<?php\n";
?>

// use yii\db\Schema;
use melkov\components\console\Migration;

class <?= $className ?> extends Migration
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
        $this->createTables();
        $this->addColumns();
    }

    public function safeDown()
    {
        $this->dropTables();
        $this->dropColumns();
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
