<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/10/10
 * Time: 13:38
 */
namespace app\modules\v1\controllers;

use app\services\auth\UsersService;
use app\tools\OutTools;
use yii\filters\auth\UserAuth;
use yii\web\Controller;


class AuthController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => UserAuth::className(),
            'except' => ['login'],
        ];
        return $behaviors;
    }

    /**
     *
     * @api {post}  /v1/auth/login  登录
     * @apiDescription 登录
     * @apiName /v1/auth/login
     * @apiGroup auth
     * @apiParam {String} username 手机号
     * @apiParam {String} password 密码
     * @apiVersion 3.1.0
     * @apiHeader {String} app_token
     * @apiHeader {String} app_lang
     * @apiHeader {String} app_type
     * @apiHeader {String} app_version
     * @apiSampleRequest /v1/auth/login
     * @apiHeaderExample {string} Header-Example:
     *     {
     *       "app_token": "wVNq2Fcg-zXVcKDYRy_vLq7niv-36As3",
     *       "app_lang": "zh",
     *       "app_type": "smt_client",
     *       "app_version": "2.4.7",
     *     }
     * @apiSuccess {String} code 200.
     * @apiSuccess {String} msg 消息
     * @apiSuccess {json} data 数据
     * @apiSuccess {json} data.user 数据
     * @apiSuccess {int} data.user.id 用户编号
     * @apiSuccess {string} data.user.username 登录账户
     * @apiSuccess {string} data.user.name 用户姓名
     * @apiSuccess {string} data.user.sex 性别 1女 2 男 0 未知
     * @apiSuccess {string} data.user.factory_name 厂编号
     * @apiSuccess {string} data.user.dept_id 厂编号
     *     * @apiSuccess {string} data.user_token 用户登录令牌
     * @apiSuccessExample {json} 正确实例:
     *{
     * "code": 200,
     * "data": {
     * "user":{
     *   "username":"",
     *   "name":"",
     *   "sex":1
     *   "dept_id":1,
     *   "factory_name":"",
     * },
     *  'user_token':''
     * },
     * "msg": "请求成功"
     * }
     * @apiError {String} code 错误码<br>
     * 0：系统错误<br>
     * @apiError {String} msg 错误消息
     * @apiErrorExample {json} 错误实例:
     * {
     * "code": "0",
     * "msg": "服务繁忙",
     * "data": []
     * }
     */
    public function actionLogin()
    {
        $username = \Yii::$app->request->post('username');
        $password = \Yii::$app->request->post('password');
        $userService = new UsersService();
        $res = $userService->login($username, $password);
        OutTools::outJsonP($res);
    }

    /**
     *
     * @api {post}  /v1/auth/get  获取登录信息
     * @apiDescription 获取登录信息
     * @apiName /auth/auth/get
     * @apiGroup auth
     * @apiVersion 3.1.0
     * @apiHeader {String} app_token
     * @apiHeader {String} app_lang
     * @apiHeader {String} app_type
     * @apiHeader {String} app_version
     * @apiSampleRequest /v1/auth/get
     * @apiHeaderExample {string} Header-Example:
     *     {
     *       "app_token": "wVNq2Fcg-zXVcKDYRy_vLq7niv-36As3",
     *       "app_lang": "zh",
     *       "app_type": "smt_client",
     *       "app_version": "2.4.7",
     *     }
     * @apiSuccess {String} code 200.
     * @apiSuccess {String} msg 消息
     * @apiSuccess {json} data 数据
     * @apiSuccess {json} data.user 数据
     * @apiSuccess {int} data.user.id 用户编号
     * @apiSuccess {string} data.user.username 登录账户
     * @apiSuccess {string} data.user.name 用户姓名
     * @apiSuccess {string} data.user.sex 性别 1女 2 男 0 未知
     * @apiSuccess {string} data.user.factory_name 厂编号
     * @apiSuccess {string} data.user.dept_id 厂编号
     * @apiSuccess {string} data.user_token 用户登录令牌
     * @apiSuccessExample {json} 正确实例:
     *
     * {
     * "code": 200,
     * "data": {
     * "user":{
     *   "username":"",
     *   "name":"",
     *   "sex":1
     *   "dept_id":1,
     *   "factory_name":"",
     * },
     * 'user_token':''
     * },
     * "msg": "请求成功"
     * }
     *
     * @apiError {String} code 错误码<br>
     * 0：系统错误<br>
     * @apiError {String} msg 错误消息
     * @apiErrorExample {json} 错误实例:
     * {
     * "code": "0",
     * "msg": "服务繁忙",
     * "data": []
     * }
     */
    public function actionGet()
    {
        $user = \Yii::$app->getUser();
        $userService = new UsersService();
        $res = $userService->get($user->getId());
        OutTools::outJsonP($res);
    }
}