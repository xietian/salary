<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/12/29
 * Time: 16:15
 */

namespace app\services\auth;


use app\models\Dept;
use app\models\Item;
use app\models\User;
use app\models\UserSalaryFixed;
use app\tools\ErrorCode;
use app\tools\OutTools;
use Exception;
use yii\helpers\ArrayHelper;

class UserSalaryFixedService
{

    public function getList($deptId = 0, $userId = 0)
    {
        //工资项目列表
        $itemList = Item::find()->where('item_type=:item_type', [':item_type' => 0])->asArray()->all();
        //用户列表
        $userList = [];
        if (empty($userId)) {
            if (empty($deptId)) {
                $userList = User::find()->orderBy("dept_id asc,name asc")->asArray()->all();
            } else {
                $userList = User::find()->where("dept_id=:dept_id", [":dept_id" => $deptId])->orderBy("name asc")->asArray()->all();
            }
        } else {
            $userList = User::find()->where("user_id=:user_id", [":user_id" => $userId])->orderBy("name asc")->asArray()->all();
        }
        $deptList = Dept::find()->asArray()->all();
        $deptMap = ArrayHelper::map($deptList, 'dept_id', 'dept_name');
        if (empty($userList) == false) {
            foreach ($userList as $item) {
                $userMap[$item['id']] = array_merge($item, ['dept_name' => isset($deptMap[$item['dept_id']]) ? $deptMap[$item['dept_id']] : ""]);
            }
            $userIdList = ArrayHelper::getColumn($userList, "id");
            $userIds = join(",", $userIdList);
            $userSalaryFixedList = UserSalaryFixed::find()->where("user_id in($userIds) and is_history=:is_history", [":is_history" => 0])->asArray()->all();
            $userSalaryFixedListMap = [];
            foreach ($userSalaryFixedList as $item) {
                $userSalaryFixedListMap[$item['user_id']][$item['item_id']] = round($item['money'], 2);
            }
        }
        return OutTools::success(array('items' => $itemList, 'users' => $userMap, 'depts' => $deptList, 'salarys' => $userSalaryFixedListMap), \Yii::t('app', 'Success'));

    }

    /**
     * 设置工资
     * @param $itemId
     * @param $userId
     * @param $money
     * @return array
     */
    public function setSalary($itemId, $userId, $money)
    {
        try {
            $salary = $this->getSalary($itemId, $userId);
            if (empty($salary) == false) {
                $salary->setAttribute("money", $money);
                $salary->save();
            } else {
                $salary = new UserSalaryFixed(['item_id' => $itemId, 'user_id' => $userId, 'money' => $money, 'is_history' => 0]);
                $salary->insert();
            }
            return OutTools::success([], \Yii::t('app', 'Success'));
        } catch (Exception $e) {
            return OutTools::error(ErrorCode::ERROR, \Yii::t('app', 'System_Busy'));
        }
    }

    public function getSalary($itemId, $userId)
    {
        return UserSalaryFixed::find()->where('item_id=:item_id and user_id=:user_id', [':item_id' => $itemId, ':user_id' => $userId])->one();
    }
}