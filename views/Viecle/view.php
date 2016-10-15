<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Viecle */

$this->title = $model->VID;
$this->params['breadcrumbs'][] = ['label' => 'Viecles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="viecle-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->VID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->VID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'VID',
            'viecle_type:ntext',
            'plate_no:ntext',
            'viecle_name:ntext',
            'brand:ntext',
            'model:ntext',
            'body_code:ntext',
            'engin_code:ntext',
            'viecle_year',
            'body_type:ntext',
            'cc',
            'seat',
            'weight',
            'owner',
        ],
    ]) ?>

</div>
