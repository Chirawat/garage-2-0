<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Viecle */

$this->title = 'Update Viecle: ' . $model->VID;
$this->params['breadcrumbs'][] = ['label' => 'Viecles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->VID, 'url' => ['view', 'id' => $model->VID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="viecle-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
