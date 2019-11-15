<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%bots}}`.
 */
class m191105_132158_create_bots_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bots}}', [
            'id'    => $this->primaryKey(),
            'name'  => $this->string(80)->notNull(),
            'descr'  => $this->string(255)->null(),
            'token' => $this->string(60)->notNull(),
            'hook'  => $this->string(180)->null()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bots}}');
    }
}
