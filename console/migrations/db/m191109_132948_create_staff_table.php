<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%jira_users}}`.
 */
class m191109_132948_create_staff_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%staff}}', [
            'id'           => $this->primaryKey(),
            'name'         => $this->string(120)->notNull(),
            'email'        => $this->string(120),
            'phone'        => $this->string(20),
            'jira_user_id' => $this->integer(11)->unique(),
            'tlg_user_id'  => $this->bigInteger()->unique(),
            'role_id'      => $this->integer(11),
            'post_id'      => $this->integer(11)
        ]);

        $this->addForeignKey('FK_staff_jira', '{{%staff}}', 'jira_user_id', '{{%jira_users}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('FK_staff_role', '{{%staff}}', 'role_id', '{{%roles}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('FK_staff_post', '{{%staff}}', 'post_id', '{{%posts}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_staff_jira', '{{%staff}}');
        $this->dropForeignKey('FK_staff_role', '{{%staff}}');
        $this->dropForeignKey('FK_staff_post', '{{%staff}}');

        $this->dropTable('{{%staff}}');
    }
}
