<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%notifies}}`.
 */
class m191105_143053_create_notifies_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notifies}}', [
            'id'         => $this->primaryKey(),
            'channel_id' => $this->integer(11)->notNull(),
            'notify'     => $this->string(255)->notNull(),
            'time'       => $this->dateTime()->notNull(),
            'delay_s'    => $this->integer()->unsigned()->null()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notifies}}');
    }
}
