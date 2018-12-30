<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/11/12
 * Time: 17:10
 */

namespace app\models;


use app\services\LoginService;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class AllUser extends ActiveRecord implements IdentityInterface
{
    public $authKey;
    public static $userType;
    public static $tableName = 'user';
    public static $userNameKey = "username";
    public static $priKey = "id";

    public static function getDb()
    {
        return Yii::$app->db;
    }

    public static function tableName()
    {
        return self::$tableName;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(array(self::$priKey => $id));
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $id = LoginService::getAllTokenInfo($token);
        if (empty($id) == false) {
            return self::findIdentity($id);
        }
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(array(self::$userNameKey => $username));
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }


    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
}