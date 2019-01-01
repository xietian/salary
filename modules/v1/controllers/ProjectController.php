<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/10/10
 * Time: 13:38
 */
namespace app\modules\v1\controllers;

use app\services\project\ProjectUserService;
use yii\filters\auth\UserAuth;
use app\services\project\UsersService;
use app\services\project\ProjectService;
use app\tools\OutTools;
use yii\web\Controller;


class ProjectController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => UserAuth::className(),
            'except' => [],
        ];
        return $behaviors;
    }

    /**
     *
     * @api {post}  /v1/project/start  发起项目
     * @apiDescription 发起项目
     * @apiName /v1/project/start
     * @apiGroup project
     * @apiParam {String} name 项目名称
     * @apiParam {String} no 项目号
     * @apiParam {String} desc 项目描述
     * @apiParam {int} start_date 开始日期
     * @apiParam {int} end_date 结束日期
     * @apiVersion 3.1.0
     * @apiHeader {String} app_token
     * @apiHeader {String} app_lang
     * @apiHeader {String} app_type
     * @apiHeader {String} app_version
     * @apiSampleRequest /v1/project/start
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
     * @apiSuccessExample {json} 正确实例:
     *{
     * "code": 200,
     * "data": {
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
    public function actionStart()
    {
        $userId = \Yii::$app->user->getId();
        $name = \Yii::$app->request->post('name');
        $no = \Yii::$app->request->post('no');
        $desc = \Yii::$app->request->post('desc');
        $startDate = \Yii::$app->request->post('start_date');
        if (empty($startDate)) {
            $startDate = 0;
        } else {
            $startDate = strtotime($startDate);
        }
        $endDate = \Yii::$app->request->post('end_date');
        if (empty($endDate)) {
            $endDate = 0;
        } else {
            $endDate = strtotime($endDate);
        }
        $isExpatriated = \Yii::$app->request->post('is_expatriated');
        $projectService = new ProjectService();
        $res = $projectService->add($name, $no, $desc, $startDate, $endDate, $isExpatriated, $userId);
        OutTools::outJsonP($res);
    }


    /**
     *
     * @api {post}  /v1/project/update  更新项目
     * @apiDescription 更新项目
     * @apiName /v1/project/update
     * @apiGroup project
     * @apiParam {String} id 项目编号
     * @apiParam {String} name 项目名称
     * @apiParam {String} no 项目号
     * @apiParam {String} desc 项目描述
     * @apiParam {int} start_date 开始日期
     * @apiParam {int} end_date 结束日期
     * @apiVersion 3.1.0
     * @apiHeader {String} app_token
     * @apiHeader {String} app_lang
     * @apiHeader {String} app_type
     * @apiHeader {String} app_version
     * @apiSampleRequest /v1/project/start
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
     * @apiSuccessExample {json} 正确实例:
     *{
     * "code": 200,
     * "data": {
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
    public function actionUpdate()
    {
        $userId = \Yii::$app->user->getId();
        $id = \Yii::$app->request->post('id');
        $name = \Yii::$app->request->post('name');
        $no = \Yii::$app->request->post('no');
        $desc = \Yii::$app->request->post('desc');
        $startDate = \Yii::$app->request->post('start_date');
        if (empty($startDate)) {
            $startDate = 0;
        } else {
            $startDate = strtotime($startDate);
        }
        $endDate = \Yii::$app->request->post('end_date');
        if (empty($endDate)) {
            $endDate = 0;
        } else {
            $endDate = strtotime($endDate);
        }
        $isExpatriated = \Yii::$app->request->post('is_expatriated');
        $projectService = new ProjectService();
        $res = $projectService->add($name, $no, $desc, $startDate, $endDate, $isExpatriated, $userId);
        OutTools::outJsonP($res);
    }


    /**
     *
     * @api {post}  /v1/project/my-project-list 我参与的项目列表
     * @apiDescription 获取我参与的项目列表
     * @apiName /v1/project/my-project-list
     * @apiParam {String} max 显示个数
     * @apiParam {String} page 第几页
     * @apiGroup project
     * @apiVersion 3.1.0
     * @apiHeader {String} app_token
     * @apiHeader {String} app_lang
     * @apiHeader {String} app_type
     * @apiHeader {String} app_version
     * @apiSampleRequest /v1/project/my-project-list
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
     * @apiSuccessExample {json} 正确实例:
     *{
     * "code": 200,
     * "data": {
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
    public function actionMyProjectList()
    {
        $userId = \Yii::$app->user->getId();
        $max = \Yii::$app->request->post('max', 10);
        if (empty($max)) {
            $max = 10;
        }
        $page = \Yii::$app->request->post('page', 1);
        if (empty($page)) {
            $page = 1;
        }
        $projectService = new ProjectService();
        $res = $projectService->getListMyUserId($userId, $max, $page);
        OutTools::outJsonP($res);
    }

    /**
     *
     * @api {post}  /v1/project/get-user-list  获取成员列表
     * @apiDescription 获取成员列表
     * @apiName /project/project/get-user-list
     *  @apiParam {String} project_id 项目编号
     * @apiGroup project
     * @apiVersion 3.1.0
     * @apiHeader {String} app_token
     * @apiHeader {String} app_lang
     * @apiHeader {String} app_type
     * @apiHeader {String} app_version
     * @apiSampleRequest /v1/project/get-user-list
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
     * @apiSuccessExample {json} 正确实例:
     *
     * {
     * "code": 200,
     * "data": {
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
    public function actionGetUserList()
    {
        $userId = \Yii::$app->user->getId();
        $projectUserService = new ProjectUserService();
        $projectId = \Yii::$app->request->post('project_id');
        $res = $projectUserService->getProjectUserList($projectId);
        OutTools::outJsonP($res);
    }


    /**
     *
     * @api {post}  /v1/project/get 获取项目详情
     * @apiDescription 获取报告详情
     * @apiName /project/project/get
     * @apiGroup project
     * @apiParam {String} project_id 项目编号
     * @apiVersion 3.1.0
     * @apiHeader {String} app_token
     * @apiHeader {String} app_lang
     * @apiHeader {String} app_type
     * @apiHeader {String} app_version
     * @apiSampleRequest /v1/project/get
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
        $userId = \Yii::$app->user->getId();
        $request = \Yii::$app->request;
        $projectId = $request->post('project_id');
        $projectService = new ProjectService();
        $res = $projectService->getProjectDetail($projectId, $userId);
        OutTools::outJsonP($res);
    }
}