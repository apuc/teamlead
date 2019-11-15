<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%jira_issues}}`.
 */
class m191107_132933_create_jira_issues_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jira_issues}}', [
            'id'         => $this->primaryKey(),
            'key'        => $this->string(32)->unique()->notNull(),
            'parent'     => $this->string(32)->null(),

            'project'    => $this->string(60)->notNull(),

            'type'       => $this->string(60)->notNull(),
            'summary'    => $this->string(255),
            'description'=> $this->text(),

            'priority_id'=> $this->integer(11)->notNull(),
            'status_id'  => $this->integer(11)->notNull(),
            'duedate'    => $this->dateTime(),
            'storypoint' => $this->smallInteger(),
            'estimate'   => $this->string(12),

            'labels'     => $this->string(512),
            'versions'   => $this->string(512),
            'sprint'     => $this->text(),
            'components' => $this->text(),

            'author_id'     => $this->integer(11)->null(),
            'assignee_id'   => $this->integer(11) ->null(),

            'last_act'   => $this->string(60),
            'json'       => $this->text(),

            'is_deleted' => $this->boolean()->defaultValue(0),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('FK_jira_author', '{{%jira_issues}}', 'author_id', '{{%jira_users}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('FK_jira_assignee', '{{%jira_issues}}', 'assignee_id', '{{%jira_users}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('FK_jira_status', '{{%jira_issues}}', 'status_id', '{{%jira_statuses}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('FK_jira_priority', '{{%jira_issues}}', 'priority_id', '{{%jira_priorities}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_jira_assignee', '{{%jira_issues}}');
        $this->dropForeignKey('FK_jira_author', '{{%jira_issues}}');

        $this->dropTable('{{%jira_issues}}');
    }
}