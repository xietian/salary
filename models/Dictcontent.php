<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dictcontent".
 *
 * @property string $id
 * @property string $dictid
 * @property string $code
 * @property string $name
 */
class Dictcontent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dictcontent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dictid'], 'integer'],
            [['code', 'name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dictid' => Yii::t('app', '关联字典表id'),
            'code' => Yii::t('app', '字典项编码'),
            'name' => Yii::t('app', '字典项名称'),
        ];
    }

    /**
     * 获取列表
     * @param $dictId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getDictContentList($dictId)
    {
        return self::find()
            ->select(['name as value', 'name as  label', 'code as id'])->asArray()->where('dictid=:dict_id', array(':dict_id' => $dictId))->all();
    }

    /**
     * 获取内容
     * @param $dictId
     * @param $code
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getDictContent($dictId, $code)
    {
        return self::find()->select(['name'])->asArray()->where('dictid=:dict_id and code=:code', array(':dict_id' => $dictId, ':code' => $code))->one();
    }
}
