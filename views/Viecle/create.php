<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Viecle */

$this->title = 'Create Viecle';
$this->params['breadcrumbs'][] = ['label' => 'Viecles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="viecle-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
