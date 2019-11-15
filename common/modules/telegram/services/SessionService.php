<?php
namespace common\modules\telegram\services;

use common\modules\telegram\records\Session;

class SessionService
{
    public static function make($chat_id, $data)
    {
        $session = Session::byChatId($chat_id) ?? new Session();

        $session->chat_id = $chat_id;
        $session->data = json_encode($data);

        $session->save();
        return $session;
    }
}