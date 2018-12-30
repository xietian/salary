<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Dictcontent */

$this->title = Yii::t('app', 'Create Dictcontent');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dictcontents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dictcontent-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
