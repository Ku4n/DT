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


        // $time = date('Y-m-d H:i:s');
        $time = '2018-3-20 17:57:12';
        $now = strtotime($time);
        $startTime = strtotime(date("14:00:00"));
        $endTime = strtotime(date("23:59:59"));
        $zero = strtotime(date("00:00:00"));
        $lastTime = strtotime(date("10:30:00"));
        $yeseterday_two = $lastTime - (3600*19); // 昨天下午14点
        $yesterday_nine = $lastTime - (3600*24); // 昨天早上9点
        $two = $lastTime - (3600*44 + 1800); // 前天下午2点

        $db = M('user');


        if($now < $startTime && $now > $lastTime){
            echo '不在报名时间内！';
        }elseif ($now > $startTime && $now < $endTime){ // 时间在当天14点到晚上24前

            $check = $db -> where(array(['userId' => $userId])) -> find();

            if($check['status'] == 1){ // 判断是当天报名还是前一天报的名

                $check = M('signup');
                $where['sign_time'] = array(array('lt' , $endTime) , array('gt' , $startTime));
                $where['userId'] = $userId;

                $res = $check -> where($where) -> order('sign_time desc') -> find();

                if($res == true){

                    echo "您今天已报名！";

                }elseif ($res == false){

                    dump(111);

                    $check = M('signup');
                    $where['sign_time'] = array(array('lt' , $lastTime) , array('gt' , $two));
                    $where['userId'] = $userId;

                    $res = $check -> where($where) -> fetchSql(true) -> find();

                    dump($res);
                }

/*                if($res == true){
                    echo '您今天已报名！';
                }else{

                }*/


            }elseif ($check['status'] == 0){ // 判断前一天是否报名

                $check = M('signup');
                $where['sign_time'] = array(array('lt' , $yesterday_nine) , array('gt' , $yeseterday_two) , 'AND');
                $where['userId'] = $userId;

                $res = $check -> where($where) -> fetchSql(true) -> order('sign_time desc') ->  find();

                dump($res);

            }




        }elseif ($now < $lastTime && $now > $zero){

            $check = $db -> where(array(['userId' => $userId])) -> find();

            if($check['status'] == 1){

                echo '您今天已报名！';

            }elseif ($check['status'] == 0){

                $check = M('signup');
                $where['sign_time'] = array(array('lt' , $yesterday_nine) , array('gt' , $yeseterday_two) , 'AND');
                $where['userId'] = $userId;

                $res = $check -> where($where) -> fetchSql(true) -> order('sign_time desc') ->  find();

                dump($res);

            }
        }
    }
}

?>