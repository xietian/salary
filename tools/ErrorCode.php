<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/12
 * Time: 23:02
 */

namespace app\tools;


class ErrorCode
{
    const SUCCESS = 200;
    const ERROR = 0;
    const TOKEN_EXPIRE = -1; //过期
    const NO_POWER = -2; //没有权限
    const NOT_FOUND_EXCEPTION = 404; //找不到
}