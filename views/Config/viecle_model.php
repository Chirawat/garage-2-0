<?php
use yii\grid\GridView;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'viecleName.name',
            'label' => 'ชื่อรถยนต์',
        ],
        [
            'attribute' => 'model',
            'label' => 'ชื่อรุ่น'
        ],
    ],
]);?>