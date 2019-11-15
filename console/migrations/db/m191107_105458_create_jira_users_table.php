<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%jira_users}}`.
 */
class m191107_105458_create_jira_users_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jira_users}}', [
            'id'        => $this->primaryKey(),
            'name'      => $this->string(80)->notNull(),
            'key'       => $this->string(80)->notNull(),
            'is_active' => $this->tinyInteger(1)->defaultValue(1)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%jira_users}}');
    }
}
