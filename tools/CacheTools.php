<?php
namespace app\tools;
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/10/10
 * Time: 20:25
 */
class CacheTools
{
    public static function get($key)
    {
        return \Yii::$app->cache->get($key);
    }

    public static function set($key, $value, $expire_time = 0)
    {
        return \Yii::$app->cache->set($key, $value, $expire_time);
    }

    public static function delete($key)
    {
        return \Yii::$app->cache->delete($key);
    }
}