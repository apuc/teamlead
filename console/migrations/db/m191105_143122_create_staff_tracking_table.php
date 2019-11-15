<?php

use console\components\BaseDbMigration;

/**
 * Handles the creation of table `{{%staff_tracking}}`.
 */
class m191105_143122_create_staff_tracking_table extends BaseDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%staff_tracking}}', [
            'id'       => $this->primaryKey(),
            'staff_id' => $this->integer(11),
            'in'       => $this->dateTime(),
            'out'      => $this->dateTime()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%staff_tracking}}');
    }
}
