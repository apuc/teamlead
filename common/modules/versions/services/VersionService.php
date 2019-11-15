<?php
namespace common\modules\versions\services;

use common\modules\labels\records\Label;
use common\modules\versions\records\Version;

class VersionService
{

    public static function make(array $array)
    {
        $model = Version::byCode($array['code']) ?? new Label();
        $model->name = $array['code'];
        $model->save();

        return $model;
    }

    public static function getVersionNames() : array
    {
        return array_map(function (Version $version) {
            return (string)$version->code;
        }, Version::find()->all());
    }
}