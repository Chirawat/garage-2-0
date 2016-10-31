<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'ยโสธรเจริญการช่าง',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            [
                'label' => 'ออกเอกสาร',
                'items' => [
                    ['label' => 'ใบเสนอราคา', 'url' => Url::to(['quotation/index'])],
                    ['label' => 'ใบแจ้งหนี้', 'url' => Url::to(['invoice/index'])],
                    ['label' => 'ใบเสร็จรับเงิน', 'url' =>'#'],
                ],
            ],
            ['label' => 'เพิ่มรูปภาพ', 'url' => '#'],
            [
                'label' => 'การเงิน',
                'items' => [
                    ['label' => 'บันทึกการเงิน', 'url' => '#'],
                    ['label' => 'สรุปประจำเดือน', 'url' =>'#'],
                ],
            ],
            [
                'label' => 'ตั้งค่า',
                'items' => [
                    ['label' => 'บริษัทประกัน', 'url' => '#'],
                    ['label' => 'ชื่อและรุ่นรถยนต์', 'url' => '#'],
                    ['label' => 'ตั้งค่าพนักงาน', 'url' => '#'],
                    '<li class="divider"></li>',
                    ['label' => 'ตรวจสอบรุ่นโปรแกรม', 'url' => '#'],
                ],
            ],
             
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">Version: Beta</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
