<?php

use melkov\components\console\Migration;
use melkov\components\helpers\DateHelper;

class m160914_235448_multilang extends Migration
{

    // yii migrate --migrationPath=@melkov/migrations/multilang

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%lang}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string()->notNull(),
            'local' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'default' => $this->boolean()->notNull()->defaultValue(false),
            'date_update' => $this->dateTime()->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->batchInsert('lang', ['url', 'local', 'name', 'default', 'date_update', 'date_create'], [
            ['en', 'en-US', 'English', false, DateHelper::now(), DateHelper::now()],
            ['ru', 'ru-RU', 'Русский', true, DateHelper::now(), DateHelper::now()],
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%lang}}');
    }
}
