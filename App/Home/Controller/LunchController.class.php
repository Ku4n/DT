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

    public function SignUp() // 报名接口
    {
        // 先假设userName 和 userId

        $userId = 'manager2651';
        $userName = "赵家宽";

       # $time = date('now');
        // $time = date('Y-m-d H:i:s');
        $time = '2018-3-20 10:19:5';
        $now = strtotime($time);
        $startTime = strtotime(date("14:00:00"));
        $endTime = strtotime(date("23:59:59"));
        $zero = strtotime(date("00:00:00"));
        $lastTime = strtotime(date("10:30:00"));


        if($now >= $lastTime && $now < $startTime)
        {

            $this -> error("还没到报名时间哦~" , "../Home/Index/index");

        }elseif ($now < $lastTime && $now >= $zero) // 时间在凌晨0点到早上9点
        {

            $add = new \Home\Model\LunchModel('signup');
            $res = $add -> Add($userId , $now , $time);


            if ($res == true){
                // $this -> success('报名成功！' , '../Index/index');
                echo 'return true';
            }elseif ($res == 0){
                // $this -> error('报名成功！,但程序有误', '../Index/index');
                echo 0;
            }elseif ($res == 1){
                // $this -> error('报名失败！', '../Index/index');
                echo 'false';
            }elseif ($res == 2){
                // $this -> error('报名失败！', '../Index/index');
                echo 'return 2';
            }

        }elseif ($now < $endTime && $now > $startTime)
        {


            $add = new \Home\Model\LunchModel('signup');
            $res = $add -> Add($userId , $now , $time);

            if ($res == true){
                // $this -> success('报名成功！' , '../Index/index');
                echo 'return true';
            }elseif ($res == 0){
                // $this -> error('报名成功！,但程序有误', '../Index/index');
                echo 0;
            }elseif ($res == 1){
                // $this -> error('报名失败！', '../Index/index');
                echo 'false';
            }elseif ($res == 2){
                // $this -> error('报名失败！', '../Index/index');
                echo 'return 2';
            }
        }
    }


    public function UnSignUp()
    {

        $userId = 'manager2651';
        $userName = "赵家宽";

        # $time = date('now');
        // $time = date('Y-m-d H:i:s');
        $time = '2018-3-20 10:19:5';
        $now = strtotime($time);
        $startTime = strtotime(date("14:00:00"));
        $endTime = strtotime(date("23:59:59"));
        $zero = strtotime(date("00:00:00"));
        $lastTime = strtotime(date("10:30:00"));

        $check = M('user');


        $db = $check -> where(['userId' => $userId]) -> find();


        $stauts = (int)$db['status'];

        // dump($stauts);

        if($stauts == 0){
            // $this ->error('请先进行报名操作！', '../Home/Lunch/SignUp');
            echo '未报名';
        }elseif ($now >= $startTime && $now < $endTime){


            $del = new \Home\Model\LunchModel('signup');
            $res = $del -> Del($userId , $now , $time);

            if ($res == true){
                // $this -> success('报名成功！' , '../Index/index');
                echo 'return true';
            }elseif ($res == 0){
                // $this -> error('报名成功！,但程序有误', '../Index/index');
                echo 0;
            }elseif ($res == 1){
                // $this -> error('报名失败！', '../Index/index');
                echo 1;
            }elseif ($res == 2){
                // $this -> error('报名失败！', '../Index/index');
                echo  2;
            }

        }elseif ($now >= $zero && $now < $lastTime){


            $del = new \Home\Model\LunchModel('signup');
            $res = $del -> Del($userId , $now , $time);

            if ($res == true){
                // $this -> success('报名成功！' , '../Index/index');
                echo 'return true';
            }elseif ($res == 0){
                // $this -> error('报名成功！,但有误', '../Index/index');
                echo 0;
            }elseif ($res == 1){
                // $this -> error('报名失败！', '../Index/index');
                echo 1;
            }elseif ($res == 2){
                // $this -> error('报名失败！', '../Index/index');
                echo  2;
            }

        }elseif ($now > $lastTime && $now < $startTime){
            $this -> error('不在时间段内，不能进行操作。' , '../Home/Lunch/Unsignup');

        }


    }


}

?>