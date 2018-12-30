<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/10/10
 * Time: 13:38
 */
namespace app\modules\v1\controllers;

use app\tools\ErrorCode;
use app\tools\OutTools;
use yii\web\Controller;


class ErrorController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionShow()
    {
        $res = OutTools::error(ErrorCode::ERROR, \Yii::t('app', 'ErrorWithCode', array(ErrorCode::NOT_FOUND_EXCEPTION)));
        OutTools::outJsonP($res);
    }
}