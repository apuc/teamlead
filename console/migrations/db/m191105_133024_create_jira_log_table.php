<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%jira_log}}`.
 */
class m191105_133024_create_jira_log_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jira_log}}', [
            'id'   => $this->primaryKey(),
            'key'  => $this->string(32)->notNull(),
            'last_act' => $this->string(60),
            'json' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%jira_log}}');
    }
}
