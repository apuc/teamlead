<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\modules\sprints\records\Sprint $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="sprint-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'jira_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Jira ID...']],

            'state' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter State...', 'maxlength' => 30]],

            'name' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Name...', 'maxlength' => 120]],

            'board_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Board ID...']],

            'start' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DateControl::classname(),'options' => ['type' => DateControl::FORMAT_DATETIME]],

            'end' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DateControl::classname(),'options' => ['type' => DateControl::FORMAT_DATETIME]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
    );
    ActiveForm::end(); ?>

</div>
