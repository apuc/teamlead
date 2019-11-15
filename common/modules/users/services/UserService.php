<?php
namespace common\modules\users\services;

use common\modules\users\records\User;

class UserService
{
    public static function getNames() : array
    {
        return array_map(function (User $user){
            return $user->name;
        }, User::find()->all());
    }

    public static function make(array $array)
    {
        $user = User::byKey($array['key']) ?? new User();

        $user->key       = $array['key'];
        $user->name      = $array['displayName'];
        $user->is_active = intval($array['active']);

        $user->save();
        return $user;
    }

}