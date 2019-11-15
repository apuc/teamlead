<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\modules\staff\records\Staff $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="staff-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'name' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Name...', 'maxlength' => 120]],

            'jira_user_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Jira User ID...']],

            'tlg_user_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Tlg User ID...', 'maxlength' => 20]],

            'role_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Role ID...']],

            'post_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Post ID...']],

            'email' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Email...', 'maxlength' => 120]],

            'phone' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Phone...', 'maxlength' => 20]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
    );
    ActiveForm::end(); ?>

</div>
