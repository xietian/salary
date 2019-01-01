<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectRole */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Project Role',
]) . $model->role_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Project Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->role_id, 'url' => ['view', 'id' => $model->role_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="project-role-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
