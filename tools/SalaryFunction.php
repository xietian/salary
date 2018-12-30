<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/12/29
 * Time: 17:03
 */

namespace app\tools;


class SalaryFunction
{
    /**
     * 计算日期
     * @param $dateTimeStamp
     * @return int
     */
    public function computeByDate($dateTimeStamp)
    {
        $y = date('Y');
        $m = date('m');
        $nextM = $m + 1;
        if ($nextM > 12) {
            $y = $y + 1;
            $nextM = 1;
        }
        $nextD = strtotime($y . "-" . $nextM . "-1");
        $totalCount = 0;
        for ($j = $dateTimeStamp; $j < $nextD; $j += 86400) {
            $w = date("w", $j);
            if ($w == 6 || $w == 0) {
                continue;
            } else {
                $totalCount++;
            }
        }
        return $totalCount;
    }


    public function computePersonalTax($sumMoney)
    {
        if ($sumMoney <= 36000) {
            return round($sumMoney * 0.03, 2);
        } else if ($sumMoney <= 144000) {
            return round($sumMoney * 0.1, 2);
        } else if ($sumMoney <= 300000) {
            return round($sumMoney * 0.2, 2);
        } else if ($sumMoney <= 420000) {
            return round($sumMoney * 0.25, 2);
        } else if ($sumMoney <= 660000) {
            return round($sumMoney * 0.3, 2);
        } else if ($sumMoney <= 960000) {
            return round($sumMoney * 0.35, 2);
        } else {
            return round($sumMoney * 0.4, 2);
        }
    }
}