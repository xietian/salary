<?php
namespace yii\behaviors;
 
use Yii;
use yii\base\Behavior;



class MessageBehavior extends Behavior
{
    
    public static function _message($string = null, $defurl = null, $time = 2, $config = null)
    {

       
        if (empty($defurl)) {
            $defurl = \Yii::$app->request->getReferrer();
            if (empty($defurl)) $defurl = "/";
        }
        if (defined("G_IN_ADMIN")) {
            if (empty($config)) {
                $config = array(
                    "titlebg" => "#549bd9",
                    "title" => "#fff"
                );
            }

            $str_url_two = array(
                "url" => '/admin',
                "text" => "返回后台首页"
            );
        } else {
            $str_url_two = array(
                "url" => "/",
                "text" => "返回首页"
            );

          
        }
        $time = intval($time);
        if ($time < 2) {
            $time = 2;
        }
        
        if (!YII_DEBUG) {
            $this->owner->layout = false;
        }

        //return $this->redirect([$defurl]);

        return $this->owner->render('/site/message', [
            'string'=>$string,
            'defurl'=>$defurl,
            'time'=>$time,
            'config'=>$config,
            'str_url_two'=>$str_url_two
        ]);
        // templates("system", "message");

    }
    
}