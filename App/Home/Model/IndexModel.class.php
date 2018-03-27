<?php
/**
 * Created by PhpStorm.
 * User: 赵家宽
 * Date: 2018/3/12
 * Time: 17:56
 */

namespace Home\Model;

use Think\Model;


class IndexModel extends Model
{

    public function login($userId)
    {

        $time = date('Y-m-d H:i:s');
        // $time = '2018-3-21 00:57:12';
        $now = strtotime($time);
        $startTime = strtotime(date("14:00:00"));
        $endTime = strtotime(date("23:59:59"));
        $zero = strtotime(date("00:00:00"));
        $lastTime = strtotime(date("10:30:00"));
        $yeseterday_two = $lastTime - (3600*20 + 1800); // 昨天下午14点
        $yesterday_nine = $lastTime - (3600*24); // 昨天早上9点
        $two = $lastTime - (3600*44 + 1800); // 前天下午2点

        $db = M('user');

        if($now < $startTime && $now >= $lastTime){
            return false;
        }elseif ($now >= $startTime && $now < $endTime){ // 时间在当天14点到晚上24前
            $check = $db -> where(array(['userId' => $userId])) -> find();
            if($check['status'] == 1){ // 判断是当天报名还是前一天报的名

                $check = M('signup');
                $where['sign_time'] = array(array('lt' , $endTime) , array('gt' , $startTime));
                $where['userId'] = $userId;
                $res = $check -> where($where) -> order('sign_time desc') -> find();

                if($res == true){ // 当天是否报名
                    return 2;
                }elseif ($res == false){ // 当天没有报名 ， 确定前天有无报名

                    $check = M('signup');
                    $where['sign_time'] = array(array('lt' , $lastTime) , array('gt' , $two));
                    $where['userId'] = $userId;
                    $res = $check -> where($where) -> find();

                    if($res == true){
                        $db = M('user');
                        $where['userId'] = $userId;
                        $update = $db -> where($where) -> fetchSql(true) -> save(['status' => 0]);

                        if($update == true){
                            return true;
                        }elseif ($update == false){
                            return 0;
                        }
                    }elseif($res == false){
                        return 1;
                    }
                }
            }elseif ($check['status'] == 0){ // 判断前一天是否报名
                return 2;
            }
        }elseif ($now < $lastTime && $now > $zero){ // 第二天0点到10点30
            $check = $db -> where(array(['userId' => $userId])) -> find();
            if($check['status'] == 1){ // 判断是当天报名还是前一天报的名

                $check = M('signup');
                $where['sign_time'] = array(array('lt' , $lastTime) , array('gt' , $yeseterday_two));
                $where['userId'] = $userId;
                $res = $check -> where($where) -> order('sign_time desc') -> find();

                if($res == true){ // 当天是否报名
                    return 2;
                }elseif ($res == false){ // 当天没有报名 ， 确定前天有无报名

                    $check = M('signup');
                    $where['sign_time'] = array(array('lt' , $lastTime) , array('gt' , $two));
                    $where['userId'] = $userId;
                    $res = $check -> where($where) -> find();

                    if($res == true){
                        $db = M('user');
                        $where['userId'] = $userId;
                        $update = $db -> where($where) -> fetchSql(true) -> save(['status' => 0]);

                        if($update == true){
                            return true;
                        }elseif ($update == false){
                            return 0;
                        }
                    }elseif($res == false){
                        return 1;
                    }
                }
            }elseif ($check['status'] == 0){// 判断前一天是否报名
                return 2;
            }
        }
    }
}

?>