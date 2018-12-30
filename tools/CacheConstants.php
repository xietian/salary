<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/10/11
 * Time: 14:51
 */

namespace app\tools;


class CacheConstants
{
    public static function getMzCache($faq_id)
    {
        return 'Faq_' . $faq_id;
    }

    public static function getTokenCache($id){
        return 'Token_' . $id;
    }
}