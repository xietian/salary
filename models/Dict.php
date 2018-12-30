<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dict".
 *
 * @property string $id
 * @property string $code
 * @property string $name
 */
class Dict extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dict';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '字典编码',
            'name' => '字典名',
        ];
    }
}
