<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\modules\statuses\records\Status $model
 */

$this->title = 'Update Status: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
