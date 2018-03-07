<?php
/**
 * Created by PhpStorm.
 * User: 赵家宽
 * Date: 2018/1/31
 * Time: 17:21
 */

namespace Home\Model;
use Think\Model;

class menuModel extends Model{

    public function menu()
    {
        session_start();

        $starttime = strtotime("14:00:00");
        $endTime = strtotime("23:59:59");
        $zero = strtotime("00:00:00");
        $nine = strtotime("09:00:00");
        $noewtime = date('Y-m-d , ')

    }
}