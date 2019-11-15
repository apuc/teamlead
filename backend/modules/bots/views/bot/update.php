<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\modules\bots\records\Bot $model
 */

$this->title = 'Update Bot: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bots', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bot-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
