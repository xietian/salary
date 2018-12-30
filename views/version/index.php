<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VersionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Versions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="version-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Version'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'version_code',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->version_code, "/version/view?id={$data->id}", []);
                }
            ],

            'version_content',
            [
                'attribute' => 'download_url',
                'format' => ['url', ['target' => '_blank']],
                'value' => function ($data) {
                    return \app\tools\OutFormat::formatImage($data->download_url);
                }
            ],
            [
                'attribute' => 'file_size',
                'format' => 'raw',
                'value' => function ($data) {
                    return \app\tools\OutFormat::formatSize($data->file_size);
                }
            ],
            [
                'attribute' => 'is_latest',
                'format' => 'raw',
                'value' => function ($data) {
                    return \app\tools\OutFormat::formatYesNo($data->is_latest);
                }
            ],
            [
                'attribute' => 'is_force',
                'format' => 'raw',
                'value' => function ($data) {
                    return \app\tools\OutFormat::formatYesNo($data->is_force);
                }
            ],
            [
                'attribute' => 'release_time',
                'format' => 'raw',
                'value' => function ($data) {
                    return \app\tools\OutFormat::formatDate($data->release_time);
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
