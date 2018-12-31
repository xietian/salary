<?php

use kartik\date\DatePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SalarySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Salaries');
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
        padding: 0px;
    }
    .form-control{
        padding: 0px;
    }
</style>
<div class="salary-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#" data-url="<?php echo \yii\helpers\Url::to('/salary/index') ?>" data-toggle="tab">工资设置</a></li>
        <li><a  href="#" data-url="<?php echo \yii\helpers\Url::to('/salary/base') ?>" data-toggle="tab">基本设置</a></li>
    </ul>
    </p>
    <p>月份：<?= DatePicker::widget([
            'id' => 'check_issue_date',
            'name' => 'check_issue_date',
            'value' => date('Y-m'),
            'options' => ['placeholder' => 'Select issue date ...'],
            'pluginOptions' => [
                'format' => 'yyyy-m',
                'todayHighlight' => true
            ]
        ]); ?>
</div> </p>
<table class="table table-striped table-bordered" style="width: 150%">
    <?php
    if (empty($itemList) == false) {
        ?>
        <tr>
            <th>姓名</th>
            <th>部门</th>
            <?php
            foreach ($itemList as $item) {
                ?>
                <th
                    style="<?php echo $item['is_hide'] ? 'display: none' : '' ?>"><?php echo $item['item_name'] ?></th>
                <?php
            }
            ?></>
        <?php
        if (empty($userList) == false) {
            foreach ($userList as $user) {
                ?>
                <tr>
                    <td>
                        <?php
                        echo $user['name'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $user['dept_name'];
                        ?>
                    </td>
                    <?php
                    foreach ($itemList as $item) {
                        ?>
                        <td style="<?php echo $item['is_hide'] ? 'display: none' : '' ?>">
                            <input type="text" class="form-control in" user_id="<?php echo $user['id'] ?>"
                                   item_id="<?php echo $item['item_id'] ?>" <?php echo $item['item_type'] != 1 ? 'readonly="readonly"' : '' ?>
                                   value="<?php echo $salaryList[$user['id']][$item['item_id']]['val'] ?>"/>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
        }

        ?>

        <?php
    } else {
        ?>
        <tr>
            <td>暂无记录</td>
        </tr>
        <?php
    }
    ?>
</table>
</div>
<?php
\app\assets\AppAsset::addJs($this, 'js/salary.js');

?>
