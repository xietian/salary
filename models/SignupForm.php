<?php
namespace app\models;
use yii\base\Model;
use app\models\User;


/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $name;
    public $created_at;
    public $updated_at;

    /**
     * @inheritdoc
     * 对数据的校验规则
     */
    public function rules()
    {
        return [
            // 对username的值进行两边去空格过滤
            ['username', 'filter', 'filter' => 'trim'],
           
            // required表示必须的，也就是说表单提交过来的值必须要有, message 是username不满足required规则时给的提示消息
            ['username', 'required', 'message' => '用户名不可以为空'],
             ['name', 'filter', 'filter' => 'trim'],
             ['name', 'required', 'message' => '真实姓名不可以为空'],
            // unique表示唯一性，targetClass表示的数据模型 这里就是说User模型对应的数据表字段username必须唯一
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => '用户名已存在.'],

            // string 字符串，这里我们限定的意思就是username至少包含2个字符，最多255个字符
            ['username', 'string', 'min' => 2, 'max' => 255],

            // 下面的规则基本上都同上，不解释了

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => '邮箱不可以为空'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'email已经被设置了.'],
            ['password', 'required', 'message' => '密码不可以为空'],
            ['password', 'string', 'min' => 6, 'tooShort' => '密码至少填写6位'],   
            // default 默认在没有数据的时候才会进行赋值
            [['created_at', 'updated_at'], 'default', 'value' => time()],
        ];
    }
    /**
     * Signs user up.
     *
     * @return true|false 添加成功或者添加失败
     */
    public function signup()
    {
        // 调用validate方法对表单数据进行验证，验证规则参考上面的rules方法
        if (!$this->validate()) {
            return null;
        }

        // 实现数据入库操作
        $user = new UserAuth();
        //var_dump($this->created_at);exit;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->name=$this->name;
        $user->created_at = $this->created_at;   
        $user->updated_at = $this->updated_at;

        // 设置密码，密码肯定要加密，暂时我们还没有实现，看下面我们有实现的代码
        $user->setPassword($this->password);

        // 生成 "remember me" 认证key
        $user->generateAuthKey();

        // save(false)的意思是：不调用User的rules再做校验并实现数据入库操作
        // 这里这个false如果不加，save底层会调用User的rules方法再对数据进行一次校验，因为我们上面已经调用Signup的rules校验过了，这里就没必要在用User的rules校验了
        return $user->save(false);
    }
}