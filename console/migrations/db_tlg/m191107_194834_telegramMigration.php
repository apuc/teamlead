<?php

use console\components\TelegramDbMigration;

/**
 * Class m191107_194834_telegramMigration
 */
class m191107_194834_telegramMigration extends TelegramDbMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(file_get_contents(__DIR__.'/structure.sql'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191107_194834_telegramMigration cannot be reverted.\n";

        return false;
    }
}
