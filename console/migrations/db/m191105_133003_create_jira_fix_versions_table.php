<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%jira_fix_versions}}`.
 */
class m191105_133003_create_jira_fix_versions_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jira_fix_versions}}', [
            'id'         => $this->primaryKey(),
            'code'       => $this->decimal(10, 1)->unique()->notNull(),
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
        $this->dropTable('{{%jira_fix_versions}}');
    }
}
