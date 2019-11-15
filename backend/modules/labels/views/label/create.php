<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\modules\labels\records\Label $model
 */

$this->title = 'Create Label';
$this->params['breadcrumbs'][] = ['label' => 'Labels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="label-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
