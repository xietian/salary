<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Version */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Versions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="version-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'version_code',
            'version_content',
            [
                'attribute' => 'download_url',
                'format' => ['url', ['target' => '_blank']],
                'value' => \app\tools\OutFormat::formatImage($model->download_url),
            ],
            [
                'attribute' => 'file_size',
                'format' => 'raw',
                'value' => \app\tools\OutFormat::formatSize($model->file_size),
            ],
            [
                'attribute' => 'is_latest',
                'format' => 'raw',
                'value' => \app\tools\OutFormat::formatYesNo($model->is_latest),
            ],
            [
                'attribute' => 'is_force',
                'format' => 'raw',
                'value' => \app\tools\OutFormat::formatYesNo($model->is_force),
            ],
            [
                'attribute' => 'release_time',
                'format' => 'raw',
                'value' => \app\tools\OutFormat::formatDate($model->release_time),
            ],
        ],
    ]) ?>

</div>
