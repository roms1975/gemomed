<?php

use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\widgets\Alert;
use dosamigos\qrcode\QrCode;

/** @var yii\web\View $this */

$this->title = 'Genomed test. Take url';
$fullUrl = $model->short_url ?: Yii::$app->request->hostInfo . '/' . $model->short_url;
?>

<?php Pjax::begin(['id' => 'links-pjax']); ?>

<div class="site-index">

    <?php
        $form = ActiveForm::begin([
            'id' => 'link-create-form',
            'options' => ['data-pjax' => true]
        ]);
    ?>

    <div class="row">
        <div class="col-md-6">
            <?= Alert::widget() ?>
            <?= $form->field($model, 'url')->textInput(['maxlength' => true])->label('Выведите url') ?>
            <?= $form->field($model, 'short_url')->textInput(['maxlength' => true, 'disabled' => true]) ?>
            <?php
                if ($model->short_url) {
                    //$qr = QrCode::png('http://localhost/cpFEZo');
                    ob_start();
                    QrCode::png($model->short_url);
                    $imageRaw = ob_get_clean();
                    $base64Image = 'data:image/png;base64,' . base64_encode($imageRaw);
                    echo '<div class="qr-code-block text-center">';
                    echo '<h4>Сканируйте QR-код:</h4>';
                    echo Html::img($base64Image);
                    echo '</div>';
                }
            ?>
            <?= Html::submitButton('ОК', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php Pjax::end(); ?>
