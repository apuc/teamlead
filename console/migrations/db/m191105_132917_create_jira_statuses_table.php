<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%jira_statuses}}`.
 */
class m191105_132917_create_jira_statuses_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jira_statuses}}', [
            'id'         => $this->primaryKey(),
            'jira_id'    => $this->integer()->unique()->notNull(),
            'name'       => $this->string(64)->unique()->notNull(),
            'descr'      => $this->string(255)->null(),
            'category'   => $this->string(120)->null(),
            'order_by'   => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%jira_statuses}}');
    }
}
