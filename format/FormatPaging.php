<?php
namespace app\format;




class FormatPaging
{

    /**
     *
     * @param $count 总个数
     * @param $max 每页显示个数
     * @param $page 当前第几页
     * @return array
     */
    static public function format($count, $max, $page)
    {
        $page_count = ceil($count / $max);
        return array('count' => $count, 'page_count' => $page_count, 'cur_page' => $page, 'page_size' => $max);
    }
}