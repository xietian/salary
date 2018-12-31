<?php
/**
 *
 * 调用java的接口集成类.
 * deprecated
 * Author: andy
 * Date: 2017/7/5
 * Time: 20:13
 */


namespace app\services\thirdpart\java\share;

use app\services\thirdpart\java\JavaApiService;

class AggregationService
{
    /**
     * 获取用户相关的信息
     * @param $userId (手机号)
     * @return array
     */
    public static function queryUserInfo($userId)
    {
        return JavaApiService::get("/aggregation/queryUserInfo", array("userId" => $userId));
    }
}