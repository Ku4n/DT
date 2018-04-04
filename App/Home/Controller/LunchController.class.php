<?php
/**
 * Created by PhpStorm.
 * User: 赵家宽
 * Date: 2018/3/15
 * Time: 20:54
 */

namespace Home\Controller;

use Think\Controller;

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods: POST, GET,OPTIONS');
header('Access-Control-Allow-Headers: Authorisation,Content-Type,Accept');

class LunchController extends Controller
{

    public function index(){
        echo 1;
    }

    public function SignUp() // 报名接口
    {
        // $userId = I('post.userId');// 获取前端返回的信息

        $userId = 'manager2651';
        // $time = date('Y-m-d H:i:s');
        $time = date("2018-03-29 09:30:00");
        $now = strtotime($time);
        $startTime = strtotime(date("14:00:00"));
        $endTime = strtotime(date("23:59:59"));
        $zero = strtotime(date("00:00:00"));
        $lastTime = strtotime(date("10:30:00"));

        if($userId == null){
            $this -> ajaxReturn('用户信息过期');
        }else{
            if($now >= $lastTime && $now < $startTime)
            {
                $this -> error("还没到报名时间哦~" , "../Home/Index/index");
            }elseif ($now < $lastTime && $now >= $zero) // 时间在凌晨0点到早上9点
            {
                $add = new \Home\Model\LunchModel('signup');
                $res = $add -> Add($userId , $now , $time);

                if ($res == true){
                    $json['msg'] = '报名成功！';
                    $json['status'] = 'true';
                    $this -> ajaxReturn($json);
                }elseif ($res == 0){
                    $json['msg'] = '报名成功！,但程序有误';
                    $json['status'] = '0000';
                    $this -> ajaxReturn($json);
                }elseif ($res == 1){
                    $json['msg'] = '报名失败！';
                    $json['status'] = '0001';
                    $this -> ajaxReturn($json);
                }elseif ($res == 2){
                    $json['msg'] = '报名失败！';
                    $json['status'] = '0002';
                    $this -> ajaxReturn($json);
                }

            }elseif ($now < $endTime && $now >= $startTime)
            {

                $add = new \Home\Model\LunchModel('signup');
                $res = $add -> Add($userId , $now , $time);

                if ($res == true){
                    $json['msg'] = '报名成功！';
                    $json['status'] = 'true';
                    $this -> ajaxReturn($json);
                }elseif ($res == 0){
                    $json['msg'] = '报名成功！,但程序有误';
                    $json['status'] = '0000';
                    $this -> ajaxReturn($json);
                }elseif ($res == 1){
                    $json['msg'] = '报名失败！';
                    $json['status'] = '0001';
                    $this -> ajaxReturn($json);
                }elseif ($res == 2){
                    $json['msg'] = '报名失败！';
                    $json['status'] = '0002';
                    $this -> ajaxReturn($json);
                }
            }
        }


    }


    public function UnSignUp()
    {

        $userId = I('post.userId');// 获取前端返回的信息

        $time = date('Y-m-d H:i:s');
        $now = strtotime($time);
        $startTime = strtotime(date("14:00:00"));
        $endTime = strtotime(date("23:59:59"));
        $zero = strtotime(date("00:00:00"));
        $lastTime = strtotime(date("10:30:00"));

        $check = M('user');


        $db = $check -> where(['userId' => $userId]) -> find();

        $stauts = (int)$db['status'];

        if($stauts == 0){
            // $this ->error('请先进行报名操作！', '../Home/Lunch/SignUp');
            echo '未报名';
        }elseif ($now >= $startTime && $now < $endTime){


            $del = new \Home\Model\LunchModel('signup');
            $res = $del -> Del($userId , $now , $time);

            if ($res == true){
                $json['msg'] = '报名成功！';
                $json['status'] = 'true';
                $this -> ajaxReturn($json);
            }elseif ($res == 0){
                $json['msg'] = '报名成功！,但程序有误';
                $json['status'] = '0000';
                $this -> ajaxReturn($json);
            }elseif ($res == 1){
                $json['msg'] = '报名失败！';
                $json['status'] = '0001';
                $this -> ajaxReturn($json);
            }elseif ($res == 2){
                $json['msg'] = '报名失败！';
                $json['status'] = '0002';
                $this -> ajaxReturn($json);
            }

        }elseif ($now >= $zero && $now < $lastTime){


            $del = new \Home\Model\LunchModel('signup');
            $res = $del -> Del($userId , $now , $time);

            if ($res == true){
                $json['msg'] = '报名成功！';
                $json['status'] = 'true';
                $this -> ajaxReturn($json);
            }elseif ($res == 0){
                $json['msg'] = '报名成功！,但程序有误';
                $json['status'] = '0000';
                $this -> ajaxReturn($json);
            }elseif ($res == 1){
                $json['msg'] = '报名失败！';
                $json['status'] = '0001';
                $this -> ajaxReturn($json);
            }elseif ($res == 2){
                $json['msg'] = '报名失败！';
                $json['status'] = '0002';
                $this -> ajaxReturn($json);
            }

        }elseif ($now > $lastTime && $now < $startTime){
            $this -> error('不在时间段内，不能进行操作。' , '../Home/Lunch/Unsignup');

        }


    }

    public function count(){

        $now = date('Y-m-d H:i:s');
        // $now = date('2018-04-04 09:23:45');
        $time = strtotime($now);
        $startTime = strtotime('14:00:00');
        $endTime = strtotime('23:59:59');
        $zero = strtotime('00:00:00');
        $lastTime = strtotime('10:30:00');
        $two = $lastTime - (3600*20 + 1800); // 昨天下午两点
        $one = strtotime('13:00:00');

        $db = M('signup');

        if($time >= $startTime && $time < $endTime){

            $where['sign_time'] = array(array('lt' , $endTime) , array('egt' , $startTime));
            $where['status'] = 1;

            $count = $db -> where($where) -> count();

            $this -> ajaxReturn($count);

        }elseif($time >= $zero && $time < $one){

            $where['sign_time'] = array(array('lt' , $lastTime) , array('egt' , $two));
            $where['status'] = 1;

            $count = $db -> where($where) -> count();

            $this -> ajaxReturn($count);

        }elseif($time >= $one && $time < $startTime){

            $count = '未到报名时间';

            $this -> ajaxReturn($count);
        }



    }


}

?>