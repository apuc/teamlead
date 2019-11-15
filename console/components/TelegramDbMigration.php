<?php
namespace console\components;

use yii\db\Migration;


class TelegramDbMigration extends Migration
{
    public function init()
    {
        $this->db = 'db_tlg';
        parent::init();
    }


}