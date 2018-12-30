<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_salary_fixed".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $item_id
 * @property string $money
 * @property integer $is_history
 */
class UserSalaryFixed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_salary_fixed';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'item_id', 'money'], 'required'],
            [['user_id', 'item_id', 'is_history'], 'integer'],
            [['money'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'user_id' => Yii::t('app', '用户编号'),
            'item_id' => Yii::t('app', '项目编号'),
            'money' => Yii::t('app', '支出金额'),
            'is_history' => Yii::t('app', '是否是历史'),
        ];
    }
}
