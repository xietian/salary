<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property integer $item_id
 * @property string $item_name
 * @property integer $item_no
 * @property integer $is_deleted
 * @property integer $item_type
 * @property string $compute
 * @property integer $is_hide
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'compute'], 'required'],
            [['item_no', 'is_deleted', 'item_type', 'is_hide'], 'integer'],
            [['compute'], 'string'],
            [['item_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => Yii::t('app', '编号'),
            'item_name' => Yii::t('app', '名称'),
            'item_no' => Yii::t('app', '排序'),
            'is_deleted' => Yii::t('app', '是否删除'),
            'item_type' => Yii::t('app', '0：固定录入，1：变动录入，2：计算'),
            'compute' => Yii::t('app', '计算公式'),
            'is_hide' => Yii::t('app', '展示'),
        ];
    }
}
