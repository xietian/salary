<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Salary */

$this->title = Yii::t('app', 'Create Salary');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Salaries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salary-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
