<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\modules\sprints\records\Sprint $model
 */

$this->title = 'Update Sprint: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sprints', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sprint-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
