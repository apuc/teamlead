<?php
namespace frontend\traits;

use common\modules\staff\records\Staff;
use Longman\TelegramBot\Request;

trait PreExecuteTrait
{
    public function checkIssue()
    {
        if (empty(\Yii::$app->issue->key)) {
            return Request::emptyResponse();
        }

        $this->jira  = \Yii::$app->get('jira');
        $this->issue = \Yii::$app->get('issue');
    }

    public function executeByRole()
    {
        $user = Staff::byTlgUserId($this->getMessage()->getFrom()->getId());

        if (empty($user->role)) {
            return $this->replyToChat('Не имеете прав на доступ. Обратитесь к администрации');
        }

        if (empty($user->jiraUser)) {
            return $this->replyToChat('Не привязан профиль Jira. Обратитесь к администрации');
        }

        if ($user->isTeamlead()) {
            return $this->executeTeamlead();
        }

        if ($user->isWorker()) {
            return $this->executeWorker();
        }
    }
}