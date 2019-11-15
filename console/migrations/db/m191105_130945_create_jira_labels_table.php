<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%jira_labels}}`.
 */
class m191105_130945_create_jira_labels_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jira_labels}}', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(64)->unique()->notNull(),
            'descr'      => $this->string(255)->null(),
            'order_by'   => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'enabled'    => $this->tinyInteger(1)->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%jira_labels}}');
    }
}
