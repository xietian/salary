<?php

namespace app\controllers;


use app\tools\VodTools;
use core\Config;
use core\DefaultAcsClient;
use core\Profile\DefaultProfile;
use vod\Request\V20170321\CreateUploadVideoRequest;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class CommonController extends Controller
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ]
        ];
    }

    public function actionTest()
    {


        $access_key_id = "LTAIT6i1ygTpXaxx";
        $access_key_secret = "6CkBqsEAc58Vf5tCEcpm6HY3YMNSET";
        $regionId = 'cn-shanghai';
        $vodTools = new VodTools($regionId, $access_key_id, $access_key_secret);
//        $res = $vodTools->create_upload_video("test","a39b9c266c7e477a1ec6bcf6faafe4f7.mp4");
//        echo "<pre>";
//        print_r($res);
//        echo "</pre>";
        $res = $vodTools->get_video_play_info('6e8ce12980ac40038780f667fcf76eed');
        echo "<pre>";
        print_r($res);
        echo "</pre>";
    }

  
}
