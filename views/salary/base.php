<?php

use kartik\date\DatePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SalarySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Base Salaries');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salary-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
    <ul class="nav nav-tabs">
        <li><a href="#" data-url="<?php echo \yii\helpers\Url::to('/salary/index') ?>" data-toggle="tab">工资设置</a></li>
        <li class="active"><a  href="#" data-url="<?php echo \yii\helpers\Url::to('/salary/base') ?>" data-toggle="tab">基本设置</a></li>
    </ul>
    </p>
<table  class="table table-striped table-bordered">
    <?php
    if (empty($itemList) == false) {
        ?>
        <tr>
            <td>姓名</td>
            <td>部门</td>
            <?php
            foreach ($itemList as $item) {
                ?>
                <td class="header"
                    style="<?php echo $item['is_hide'] ? 'display: none' : '' ?>"><?php echo $item['item_name'] ?></td>
                <?php
            }
            ?></>
        <?php
        if (empty($userList) == false) {
            foreach ($userList as $user) {
                ?>
                <tr>
                    <td class="form-label">
                        <?php
                        echo $user['name'];
                        ?>
                    </td>
                    <td class="form-label">
                        <?php
                        echo $user['dept_name'];
                        ?>
                    </td>
                    <?php
                    foreach ($itemList as $item) {
                        ?>
                        <td style="<?php echo $item['is_hide'] ? 'display: none' : '' ?>">
                            <input type="text" class="form-control in_base" user_id="<?php echo $user['id'] ?>"
                                   item_id="<?php echo $item['item_id'] ?>"
                                   value="<?php echo isset($salaryList[$user['id']][$item['item_id']])?$salaryList[$user['id']][$item['item_id']]:"" ?>"/>
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
