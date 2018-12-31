<?php
/**
 *
 * 调用java的接口类.
 * deprecated
 * Author: andy
 * Date: 2017/7/5
 * Time: 20:13
 */

namespace app\services\thirdpart\java\share;

use app\services\thirdpart\java\JavaApiService;

class AccountService
{

    /**
     * 添加账户
     * @param $userId
     * @param $userName
     * @return mixed
     */
    public static function addAccount($userId, $userName)
    {
        return JavaApiService::post("/account/addAccount", array("userId" => $userId, "userName" => $userName));
    }

	/**
	 * 调用java接口添加机构账户
	 *
	 * @param int $userId 机构ID
	 * @param string $userName userName为机构名
	 *
	 * @return array
	 */
	public static function addOrgAccount( $userId, $userName ) {
		return JavaApiService::post("/account/addAccount", array("userId" => $userId, "userName" => $userName));
	}
}