<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VersionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="version-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'version_code') ?>

    <?= $form->field($model, 'version_content') ?>

    <?= $form->field($model, 'download_url') ?>

    <?= $form->field($model, 'file_size') ?>

    <?php // echo $form->field($model, 'is_latest') ?>

    <?php // echo $form->field($model, 'is_force') ?>

    <?php // echo $form->field($model, 'release_time') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
