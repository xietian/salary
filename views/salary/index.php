<?php

use kartik\date\DatePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SalarySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Salaries');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salary-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Salary'), ['create'], ['class' => 'btn btn-success']) ?>
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
<table>
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
                                   item_id="<?php echo $item['item_id'] ?>" <?php echo  $item['item_type'] != 1 ? 'readonly="readonly"' : '' ?>
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
