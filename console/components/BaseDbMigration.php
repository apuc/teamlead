<?php
namespace console\components;

use yii\db\Migration;


class BaseDbMigration extends Migration
{
    public function init()
    {
        $this->db = 'db';
        parent::init();
    }
}