<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dept".
 *
 * @property integer $dept_id
 * @property string $dept_name
 * @property integer $dept_no
 */
class Dept extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dept';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dept_name'], 'required'],
            [['dept_no'], 'integer'],
            [['dept_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dept_id' => Yii::t('app', '部门编号'),
            'dept_name' => Yii::t('app', '部门名称'),
            'dept_no' => Yii::t('app', '排序'),
        ];
    }
}
