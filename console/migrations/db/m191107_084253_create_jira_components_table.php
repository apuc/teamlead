<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%jira_components}}`.
 */
class m191107_084253_create_jira_components_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jira_components}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%jira_components}}');
    }
}
