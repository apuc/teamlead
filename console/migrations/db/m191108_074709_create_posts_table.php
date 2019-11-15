<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%posts}}`.
 */
class m191108_074709_create_posts_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%posts}}', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(80)->unique()->notNull(),
            'descr'      => $this->string(255),
            'created_at' => 'timestamp NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%posts}}');
    }
}
