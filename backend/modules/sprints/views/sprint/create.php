<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\modules\sprints\records\Sprint $model
 */

$this->title = 'Create Sprint';
$this->params['breadcrumbs'][] = ['label' => 'Sprints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprint-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
