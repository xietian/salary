<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/5/4
 * Time: 10:21
 */

namespace app\services\thirdpart\wx\wechat;


class WechatQrScene
{
    const ACT_TYPE_DOCTOR_FRIENDS = "DoctorUser";
    const ACT_TYPE_USER_FELLOW = "UserFellow";
    const ACT_TYPE_USER_RECOMMEND = "UserRecommend";
    public $id;
    public $name;
    public $type;
    public $act_type;
    public $act_id;
    public $act_state = 0;
    public $product;

    function __toString()
    {
        return json_encode(get_object_vars($this));
    }


    public function __construct($id, $name, $type, $act_type, $act_id, $product)
    {
        if (empty($id) == false) {
            $this->id = $id;
            $this->name = $name;
            $this->type = $type;
            $this->act_type = $act_type;
            $this->act_id = !empty($act_id) ? $act_id : $id;
            $this->product = $product;
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }


    /**
     * @param $data
     * @return mixed
     */
    public static function hash($data)
    {
        return base64_encode(json_encode($data));
    }

    /**
     * 解码
     * @param $data
     * @return mixed
     */
    public static function unHash($data)
    {
        return json_decode(base64_decode($data), true);
    }

    public function encode()
    {
        return self::hash(get_object_vars($this));
    }

    /**
     * 判断是否操作的状态
     * @param $openUserId
     * @return bool|mixed
     */
    public function checkActState($openUserId)
    {
        $flag = false;
        if (empty($this->act_id)) {
            $this->act_id = $this->id;
        }
        $platClass = $this->act_type . 'Act';
        $classPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'qrsceneact' . DIRECTORY_SEPARATOR . $platClass . '.php';
			   \Yii::error(var_export($classPath, true),'yii\error\subscribe');
        if (file_exists($classPath)) {
            $platClass2 = '\app\services\thirdpart\wx\wechat\qrsceneact\\' . $platClass;
            $platClassInstant = new $platClass2();
			   \Yii::error(var_export($platClassInstant, true),'yii\error\subscribe');
            if (method_exists($platClassInstant, 'check')) {
                $flag = call_user_func_array(array($platClassInstant, 'check'), [$this, $openUserId]);
            }
        }
        $this->act_state = $flag !== false ? 1 : 0;
        return $flag;
    }


}
