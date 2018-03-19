<?php
/**
 * Created by PhpStorm.
 * User: 赵家宽
 * Date: 2018/3/15
 * Time: 20:54
 */

namespace Home\Controller;

use Think\Controller;

class LunchController extends Controller
{

    public function index(){
        echo 1;
    }

    public function Signup() // 报名接口
    {
        // 先假设userName 和 userId

        $userId = 'manager2651';
        $userName = "赵家宽";

       # $time = date('now');
        $time = date('now');
        $now = strtotime($time);
        $startTime = strtotime("14:00:00");
        $endTime = strtotime("23:59:59");
        $zero = strtotime("00:00:00");
        $lastTime = strtotime("10:30:00");

        if($now >= $lastTime && $now < $startTime)
        {
            $this -> error("还没到报名时间哦~" , "../Index/index");
        }elseif ($now < $lastTime && $now >= $zero) // 时间在凌晨0点到早上9点
        {

            $add = new \Home\Model\LunchModel('signup');
            $res = $add -> Create($userName , $userId , $now , $time);


            if ($res == true){
                // $this -> success('报名成功！' , '../Index/index');
                echo 'return true';
            }elseif ($res == 0){
                $this -> error('报名失败！,提交用户信息有误', '../Index/index');
            }elseif ($res == 1){
                // $this -> error('报名失败！,提交用户信息有误', '../Index/index');
                echo 'return 1';
            }elseif ($res == 2){
                // $this -> error('报名失败！', '../Index/index');
                echo 'return 2';
            }elseif ($res == 3){
                // $this -> error('报名成功，但程序有误，请联系管理员！', '../Index/index');
                echo 'return 3';
            }
        }elseif ($now < $endTime && $now > $startTime)
        {
            $add = new \Home\Model\LunchModel('signup');
            $res = $add -> Create($userName , $userId , $now , $time);

            if ($res == true){
                // $this -> success('报名成功！' , '../Index/index');
                echo 'return true';
            }elseif ($res == 0){
                $this -> error('报名失败！,提交用户信息有误', '../Index/index');
            }elseif ($res == false){
                // $this -> error('报名失败！,提交用户信息有误', '../Index/index');
                echo 'false';
            }elseif ($res == 2){
                // $this -> error('报名失败！', '../Index/index');
                echo 'return 2';
            }elseif ($res == 3){
                // $this -> error('报名成功，但程序有误，请联系管理员！', '../Index/index');
                echo 'return 3';
            }
        }
    }


    public function Unsignup()
    {

        $userId = 'manager2651';
        $userName = "赵家宽";

        $time = date('now');
        $now = strtotime($time);
        $startTime = strtotime("14:00:00");
        $endTime = strtotime("23:59:59");
        $zero = strtotime("00:00:00");
        $lastTime = strtotime("10:30:00");

        $check = M('user');


        $db = $check -> where(['userId' => $userId]) -> find();


        $stauts = (int)$db['status'];

        // dump($stauts);

        if($stauts == 0){
            $this ->error('请先进行报名操作！', '../Home/Lunch/SignUp');
        }elseif ($now >= $startTime && $now < $endTime){

            $del = new \Home\Model\LunchModel('signup');
            $res = $del -> Del($userName , $userId , $now , $time);

            if ($res == true){
                // $this -> success('报名成功！' , '../Index/index');
                echo 'return true';
            }elseif ($res == 0){
                $this -> error('报名失败！,提交用户信息有误', '../Index/index');
            }elseif ($res == false){
                // $this -> error('报名失败！,提交用户信息有误', '../Index/index');
                echo 'false';
            }elseif ($res == 2){
                // $this -> error('报名失败！', '../Index/index');
                echo 'return 2';
            }elseif ($res == 3){
                // $this -> error('报名成功，但程序有误，请联系管理员！', '../Index/index');
                echo 'return 3';
            }

        }elseif ($now >= $zero && $now < $lastTime){

            $del = new \Home\Model\LunchModel('signup');
            $res = $del -> Del($userName , $userId , $now , $time);

            if ($res == true){
                // $this -> success('报名成功！' , '../Index/index');
                echo 'return true';
            }elseif ($res == 0){
                $this -> error('报名失败！,提交用户信息有误', '../Index/index');
            }elseif ($res == false){
                // $this -> error('报名失败！,提交用户信息有误', '../Index/index');
                echo 'false';
            }elseif ($res == 2){
                // $this -> error('报名失败！', '../Index/index');
                echo 'return 2';
            }elseif ($res == 3){
                // $this -> error('报名成功，但程序有误，请联系管理员！', '../Index/index');
                echo 'return 3';
            }

        }elseif ($now > $lastTime && $now < $startTime){
            $this -> error('不在时间段内，不能进行操作。' , '../Home/Lunch/Unsignup');
        }


    }


}

?>