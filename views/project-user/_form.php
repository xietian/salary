<?php

use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'project_id')->dropDownList(\app\tools\CommonFunc::getProjectListMap()) ?>
    <?= $form->field($model, 'user_id')->textInput() ?>
    <div class="form-group field-project-user-user_name">
        <label class="control-label" for="project-user-user_name">用户</label>
        <?php
        echo AutoComplete::widget([
            'name' => 'user_name',
            'value' => \app\tools\CommonFunc::getUser($model->user_id),
            'id' => 'user_name',
            'options' => ['class' => 'form-control autoC'],
            'clientOptions' => [
                'source' => new JsExpression("function(request, response) {
                $.getJSON('" . \yii\helpers\Url::to('/project-user/query') . "', {
                    q: request.term
                }, response);}"),
                'autoFill' => true,
                'minLength' => 2,
                'select' => new JsExpression("function( event, ui ) {
                event.preventDefault();
                $('#user_name').val(ui.item.label);
                $('#projectuser-user_id').val(ui.item.value);
            }"),
                'focus' => new JsExpression("function( event, ui ) {
                            return false;
                        }"),
                '_renderItem'=> new JsExpression("function(ul, item){
                    return $('<li>').attr('data-value', item.value).append( item.label ).appendTo( ul );
                }")
        ]]);
        ?>
        <div class="help-block"></div>
    </div>

    <?= $form->field($model, 'role_id')->dropDownList(\app\tools\CommonFunc::getProjectRoleListMap()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
