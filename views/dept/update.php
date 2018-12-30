<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Dept */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Dept',
]) . $model->dept_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Depts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dept_id, 'url' => ['view', 'id' => $model->dept_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="dept-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
