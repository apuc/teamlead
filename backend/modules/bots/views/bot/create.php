<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\modules\bots\records\Bot $model
 */

$this->title = 'Create Bot';
$this->params['breadcrumbs'][] = ['label' => 'Bots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bot-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
