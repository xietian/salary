<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->name, "/user/view?id={$data->id}", []);
                }
            ],
            [
                'attribute' => 'sex',
                'format' => 'raw',
                'value' => function ($data) {
                    return \app\tools\OutFormat::formatSex($data->sex);
                },
            ],
            'username',
            [
                'attribute' => 'dept_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return \app\tools\OutFormat::formatDept($data->dept_id);
                },
            ],
            // 'password_hash',
            // 'password_reset_token',
            // 'email:email',
            // 'status',
            // 'created_at',
            // 'updated_at',
            // 'dept_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
