<?php

use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sex')->radioList(\app\tools\CommonFunc::getSexList()) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <div style="display: none">
        <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>
    </div>
    <div style="display: none">
    <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>
    </div>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <div style="display: none">
        <?= $form->field($model, 'status')->hiddenInput() ?>

        <?= $form->field($model, 'created_at')->hiddenInput() ?>

        <?= $form->field($model, 'updated_at')->hiddenInput() ?>
    </div>
    <div class="form-group user-nation-dept_id">
        <label class="control-label" for="user-dept_id">工厂</label>
        <?php
        $data = \app\tools\CommonFunc::getFactoryList();
        echo AutoComplete::widget([
            'name' => 'factory_name',
            'value' => \app\tools\OutFormat::formatDept($model->dept_id),
            'id' => 'factory_name',
            'options' => ['class' => 'form-control autoC'],
            'clientOptions' => [
                'source' => $data,
                'autoFill' => true,
                'minLength' => 0,
                'select' => new JsExpression("function( event, ui ) {
                $('#user-dept_id').val(ui.item.id);
            }"),
                'focus' => new JsExpression("function( event, ui ) {
                            return false;
                        }")
            ]
        ]);
        ?>
        <div class="help-block"></div>
    </div>
    <?= Html::activeHiddenInput($model, 'dept_id') ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->beginBlock('script') ?>
$(function(){
$(".autoC").focus(function(){
$(this).autocomplete("search");
})
});

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['script'], \yii\web\View::POS_END); ?>

