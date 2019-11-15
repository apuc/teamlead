<?php
namespace common\modules\labels\services;

use common\modules\labels\records\Label;

class LabelService
{

    public static function make(array $array)
    {
        $model = Label::byName($array['name']) ?? new Label();
        $model->name = $array['name'];
        $model->save();

        return $model;
    }

    public static function getLabelName() : array
    {
        return array_map(function (Label $label) {
            return $label->name;
        }, Label::find()->all());
    }
}