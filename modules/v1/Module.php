<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/12
 * Time: 14:47
 */

namespace app\modules\v1;
use Yii;
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        Yii::$app->user->identityClass='app\models\AllUser';
        Yii::$app->errorHandler->errorAction='v1/error/show';
        parent::init();

        // custom initialization code goes here
    }
}