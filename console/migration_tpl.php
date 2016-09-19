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

    public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "<?= $className ?> cannot be reverted.\n";
        return false;
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
