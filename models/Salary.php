<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "salary".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $money
 * @property integer $item_id
 * @property integer $date
 */
class Salary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'salary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'money', 'item_id', 'date'], 'required'],
            [['user_id', 'item_id', 'date'], 'integer'],
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
            'money' => Yii::t('app', '金额'),
            'item_id' => Yii::t('app', '工资项'),
            'date' => Yii::t('app', '发送日期'),
        ];
    }
}
