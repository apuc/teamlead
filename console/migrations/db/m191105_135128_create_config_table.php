<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%config}}`.
 */
class m191105_135128_create_config_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%config}}', [
            'id'   => $this->primaryKey(),
            'code' => $this->string(120)->notNull(),
            'value'=> $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%config}}');
    }
}
