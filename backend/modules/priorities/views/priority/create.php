<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\modules\priorities\records\Priority $model
 */

$this->title = 'Create Priority';
$this->params['breadcrumbs'][] = ['label' => 'Priorities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="priority-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
