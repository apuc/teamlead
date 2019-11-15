<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%jira_sprints}}`.
 */
class m191105_143502_create_jira_sprints_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jira_sprints}}', [
            'id'       => $this->primaryKey(),
            'jira_id'  => $this->integer()->notNull(),
            'state'    => $this->string(30)->notNull(),
            'name'     => $this->string(120)->notNull(),
            'start'    => $this->dateTime(),
            'end'      => $this->dateTime(),
            'goal'     => $this->string(250),
            'board_id' => $this->integer(),
            'board_name' => $this->string(120),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%jira_sprints}}');
    }
}
