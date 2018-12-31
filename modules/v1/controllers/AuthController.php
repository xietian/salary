<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/10/10
 * Time: 13:38
 */
namespace app\modules\v1\controllers;

use app\services\auth\UsersService;
use app\services\thirdpart\wx\xcx\OauthService;
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
            'except' => ['login', 'auto-login', 'get-open-info'],
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
     * @apiParam {String} openid 第三方编号
     * @apiParam {String} appid 小程序APPID
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
        $openId = \Yii::$app->request->post('openid');
        $appId = \Yii::$app->request->post('appid');
        $appId = OauthService::getAppId($appId);
        $userService = new UsersService();
        $res = $userService->login($username, $password, $openId, $appId);
        OutTools::outJsonP($res);
    }


    /**
     *
     * @api {post}  /v1/auth/auto-login  自动登录
     * @apiDescription 登录
     * @apiName /v1/auth/auto-login
     * @apiGroup auth
     * @apiParam {String} openid 第三方编号
     * @apiParam {String} appid 小程序APPID
     * @apiVersion 3.1.0
     * @apiHeader {String} app_token
     * @apiHeader {String} app_lang
     * @apiHeader {String} app_type
     * @apiHeader {String} app_version
     * @apiSampleRequest /v1/auth/auto-login
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
    public function actionAutoLogin()
    {
        $openId = \Yii::$app->request->post('openid');
        $appId = \Yii::$app->request->post('appid');
        $appId = OauthService::getAppId($appId);
        $userService = new UsersService();
        $res = $userService->autoLogin($openId, $appId);
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


    /**
     *
     * @api {post}  /v1/auth/get-open-info  获取openinfo
     * @apiDescription 获取openinfo
     * @apiName /auth/auth/get-open-info
     * @apiGroup auth
     *  @apiParam {String} code 微信code
     * @apiVersion 3.1.0
     * @apiHeader {String} app_token
     * @apiHeader {String} app_lang
     * @apiHeader {String} app_type
     * @apiHeader {String} app_version
     * @apiSampleRequest /v1/auth/get-open-info
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
     * @apiSuccess {json} data.openid 小程序openid
     * @apiSuccessExample {json} 正确实例:
     *
     * {
     * "code": 200,
     * "data": {
     * 'open_id':''
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
    public function actionGetOpenInfo()
    {
        $request = \Yii::$app->request;
        $code = $request->post('code');
        $userService = new UsersService();
        $res = $userService->getOpenInfo($code);
        OutTools::outJsonP($res);
    }
}