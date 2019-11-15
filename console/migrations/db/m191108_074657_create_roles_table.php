<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%roles}}`.
 */
class m191108_074657_create_roles_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%roles}}', [
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
        $this->dropTable('{{%roles}}');
    }
}
