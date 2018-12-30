<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Version */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="version-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'version_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'version_content')->textarea(['maxlength' => true]) ?>
    <div style="display: none;">
        <?= $form->field($model, 'download_url')->textInput(['maxlength' => true]) ?>
    </div>
    <?php
    echo $form->field($model, 'downloadUrlUpload')->widget(
        '\trntv\filekit\widget\Upload',
        [
            'url' => ['upload'],
            'sortable' => true,
            'maxFileSize' => 30 * 1024 * 1024, // 10 MiB
            'maxNumberOfFiles' => 1,
            'clientOptions' => ['done' => new JsExpression("function(e, data) {
                        }"),]
        ]
    );
    ?>

    <?= $form->field($model, 'is_force')->radioList(\app\tools\CommonFunc::getYesNoList()) ?>
    <div style="display: none;">
        <?= $form->field($model, 'is_latest')->radioList(\app\tools\CommonFunc::getYesNoList()) ?>
    </div>
    <div style="display: none;"><?= $form->field($model, 'release_time')->textInput() ?></div>
    <div style="display: none;"><?= $form->field($model, 'file_size')->textInput() ?></div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
