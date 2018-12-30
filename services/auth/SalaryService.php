<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/12/29
 * Time: 11:21
 */

namespace app\services\auth;


use app\models\Dept;
use app\models\Item;
use app\models\Salary;
use app\models\User;
use app\models\UserSalaryFixed;
use app\tools\Constants;
use app\tools\ErrorCode;
use app\tools\OutTools;
use app\tools\SalaryFunction;
use League\Flysystem\Exception;
use yii\helpers\ArrayHelper;

class SalaryService
{


    /**
     * 获取列表
     * @param $deptId
     * @param $userId
     * @param $dateDate
     * @return array
     */
    public function getListByMonth($dateDate, $deptId = 0, $userId = 0)
    {
        //工资项目列表
        $itemList = Item::find()->asArray()->orderBy("item_no asc")->all();
        if (empty($userId)) {
            $salaryList = Salary::find()->where("date=:date", [":date" => $dateDate])->asArray()->all();
        } else {
            $salaryList = Salary::find()->where("date=:date and user_id=:user_id", [":date" => $dateDate, ":user_id" => $userId])->asArray()->all();
        }
        //已录入的工资
        $salaryListMap = [];
        if (empty($salaryList) == false) {
            foreach ($salaryList as $item) {
                $salaryListMap[$item['user_id']][$item['item_id']] = $item['money'];
            }
        }

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
        //用户
        $userMap = [];
        //固定工资
        $userSalaryFixedListMap = [];
        $salaryInputMap = [];
        //部门
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
                $userSalaryFixedListMap[$item['user_id']][$item['item_id']] = $item['money'];
            }


            /**
             * 工资列表
             */

            foreach ($userMap as $userId => $user) {
                foreach ($itemList as $item) {
                    $val = isset($salaryListMap[$userId][$item['item_id']]) ? $salaryListMap[$userId][$item['item_id']] : (isset($userSalaryFixedListMap[$userId][$item['item_id']]) ? $userSalaryFixedListMap[$userId][$item['item_id']] : null);
                    $salaryInputMap[$userId][$item['item_id']] = array_merge($item, ['val' => round($val, 2)]);
                }
            }
            $globalTotalData = array();
            $this->computerUserSalary($salaryInputMap, $globalTotalData, $dateDate);
        }

        return OutTools::success(array('items' => $itemList, 'users' => $userMap, 'depts' => $deptList, 'salarys' => $salaryInputMap), \Yii::t('app', 'Success'));
    }

    /**
     * 计算工资
     * @param $userSalaryInputMap
     * @param $globalTotalData
     * @param $dateDate
     */
    public function computerUserSalary(&$userSalaryInputMap, &$globalTotalData, $dateDate)
    {
        foreach ($userSalaryInputMap as $userId => $itemA) {
            foreach ($itemA as $item) {
                if (empty($item['compute']) == false) {
                    $c = json_decode($item['compute'], true);
//                    if($item['item_id'] == 32){
//                        var_dump($item['compute']);
//                    }
                    if (empty($c['method']) == false) {
                        $val = $this->computerUserSalaryByItem($userId, $c, $userSalaryInputMap, $globalTotalData, $dateDate, 0);

                        $userSalaryInputMap[$userId][$item['item_id']]['val'] = $val;
                    }
                }
            }
        }
    }

    public function computerUserSalaryByItem($userId, $c, $userSalaryInputMap, &$globalTotalData, $dateDate, $val = 0)
    {
        if ($c['method'] == 'user_func' && isset($c['func_name'])) {
            $obj = new SalaryFunction();
            if (!isset($c['item'])) {
                $count = call_user_func_array(array($obj, $c['func_name']), [$dateDate]);
            } else {
                //汇总某项数据
                if (empty($c['grand_total']) == false) {
                    $val2 = isset($userSalaryInputMap[$userId][$c['item'][0]]['val']) ? round($userSalaryInputMap[$userId][$c['item'][0]]['val'], 2) : 0;
                    $year = date('Y', $dateDate);
                    $beginDate = strtotime($year . "-1-1");
                    if ($beginDate == $dateDate) {
                        $globalTotalData[$userId][$c['item'][0]] = $val2;
                    } else {
                        if (!isset($globalTotalData[$userId][$c['item'][0]])) {
                            $globalTotalData = $this->sumMoney($c['item'][0], $beginDate, $dateDate);
                        }
                        $globalTotalData[$userId][$c['item'][0]] = isset($globalTotalData[$userId][$c['item'][0]]) ? $globalTotalData[$userId][$c['item'][0]] + $val2 : $val2;
                    }
                    $count = call_user_func_array(array($obj, $c['func_name']), [$globalTotalData[$userId][$c['item'][0]]]);
                }
            }
            $val = $val + $count;
        } else if ($c['method'] == 'sum' && isset($c['init']) && isset($c['item'])) {
            if (is_array($c['init'])) {
                foreach ($c['init'] as $ritem) {
                    $val += isset($userSalaryInputMap[$userId][$ritem]['val']) ? $userSalaryInputMap[$userId][$ritem]['val'] : 0;
                }
            } else {
                $val += $c['init'];
            }
            foreach ($c['item'] as $litem) {
                if (is_array($litem)) {
                    $val = $this->computerUserSalaryByItem($userId, $litem, $userSalaryInputMap, $globalTotalData, $dateDate, $val);
                } else {
                    $val += isset($userSalaryInputMap[$userId][$litem]['val']) ? $userSalaryInputMap[$userId][$litem]['val'] : 0;
                }
            }
        } else if ($c['method'] == 'div' && isset($c['init']) && isset($c['item'])) {
            if (is_array($c['init'])) {
                foreach ($c['init'] as $ritem) {
                    $val += isset($userSalaryInputMap[$userId][$ritem]['val']) ? $userSalaryInputMap[$userId][$ritem]['val'] : 0;
                }
            } else {
                $val += $c['init'];
            }
            foreach ($c['item'] as $litem) {
                if (is_array($litem)) {
                    $val = $this->computerUserSalaryByItem($userId, $litem, $userSalaryInputMap, $globalTotalData, $dateDate, $val);
                } else {
                    $val = isset($userSalaryInputMap[$userId][$litem]['val']) && $userSalaryInputMap[$userId][$litem]['val'] != 0 ? round($val / $userSalaryInputMap[$userId][$litem]['val'], 2) : 0;
                }
            }
        } else if ($c['method'] == 'multi') {
            if (is_array($c['init'])) {
                foreach ($c['init'] as $ritem) {
                    $val += isset($userSalaryInputMap[$userId][$ritem]['val']) ? $userSalaryInputMap[$userId][$ritem]['val'] : 0;
                }
            } else {
                $val += $c['init'];
            }
            foreach ($c['item'] as $litem) {
                if (is_array($litem)) {
                    $val = $this->computerUserSalaryByItem($userId, $litem, $userSalaryInputMap, $globalTotalData, $dateDate, $val);
                } else {
                    $val = isset($userSalaryInputMap[$userId][$litem]['val']) ? round($val * $userSalaryInputMap[$userId][$litem]['val'], 2) : 0;
                }
            }
        } else if ($c['method'] == 'sub') {
            if (is_array($c['init'])) {
                foreach ($c['init'] as $ritem) {
                    $val += isset($userSalaryInputMap[$userId][$ritem]['val']) ? $userSalaryInputMap[$userId][$ritem]['val'] : 0;
                }
            } else {
                $val += $c['init'];
            }
            foreach ($c['item'] as $litem) {
                if (is_array($litem)) {
                    $val = $this->computerUserSalaryByItem($userId, $litem, $userSalaryInputMap, $globalTotalData, $dateDate, $val);
                } else {
                    if (is_string($litem)) {
                        $val -= intval($litem);
                    } else {
                        $val -= isset($userSalaryInputMap[$userId][$litem]['val']) ? $userSalaryInputMap[$userId][$litem]['val'] : 0;

                    }
                }
            }
        }
        return $val;
    }

    /**
     * 累计金额
     * @param $itemId
     * @param $fromDate
     * @param $toDate
     * @return array|\yii\db\ActiveRecord[]
     */
    public function sumMoney($itemId, $fromDate, $toDate)
    {
        $list = Salary::find()->select("user_id, sum(money) as total")->where("item_id=:item_id and date>=:from_date and date<:to_date", array(":item_id" => $itemId, ":from_date" => $fromDate, ":to_date" => $toDate))->groupBy("user_id")->asArray()->all();
        $map = [];
        foreach ($list as $rec) {
            $map[$rec['user_id']][$itemId] = $rec['total'];
        }
        return $map;
    }

    /**
     * 计算他的依赖工资
     * @param $itemId
     * @param $userId
     * @param $money
     * @param $date
     */

    public function computerRSalary($itemId, $userId, $money, $date)
    {

    }

    /**
     * 设置工资
     * @param $itemId
     * @param $userId
     * @param $money
     * @param $date
     * @return array
     */
    public function setSalary($itemId, $userId, $money, $date)
    {
        try {
            $salary = $this->getSalary($itemId, $userId, $date);
            if (empty($salary) == false) {
                $salary->setAttribute("money", $money);
                $salary->save();
            } else {
                $salary = new Salary(['item_id' => $itemId, 'user_id' => $userId, 'money' => $money, 'date' => $date]);
                $salary->insert();
            }
            return OutTools::success([], \Yii::t('app', 'Success'));
        } catch (Exception $e) {
            return OutTools::error(ErrorCode::ERROR, \Yii::t('app', 'System_Busy'));
        }
    }

    public function getSalary($itemId, $userId, $date)
    {
        return Salary::find()->where('item_id=:item_id and user_id=:user_id and date=:date', [':item_id' => $itemId, ':user_id' => $userId, 'date' => $date])->one();
    }


}