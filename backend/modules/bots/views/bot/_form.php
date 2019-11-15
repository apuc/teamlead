<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\modules\bots\records\Bot $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="bot-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'name' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Name...', 'maxlength' => 80]],

            'token' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Token...', 'maxlength' => 60]],

            'hook' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Hook...', 'maxlength' => 180]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
    );
    ActiveForm::end(); ?>

</div>
