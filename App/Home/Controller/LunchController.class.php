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
        $time = date('2018-03-19 10:04:43');
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
            $res = $add -> Add($userName , $userId , $now , $time);


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
            $res = $add -> Add($userName , $userId , $now , $time);

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


    public function UnSignUp()
    {

        $userId = 'manager2651';
        $userName = "赵家宽";

        $time = date('2018-03-16 10:04:43');
        $now = strtotime($time);
        $startTime = strtotime("14:00:00");
        $endTime = strtotime("23:59:59");
        $zero = strtotime("00:00:00");
        $lastTime = strtotime("10:30:00");

        $check = M('user');


        $db = $check -> where(['userId' => $userId]) -> find();


        $stauts = (int)$db['status'];

        // dump($stauts);

        if($stauts !== 1){
            $this ->error('请先进行报名操作！', '../Home/Lunch/SignUp');
        }elseif ($now > $startTime && $now < $endTime){

            $del = new \Home\Model\LunchModel('signup');
            $res = $del -> Del($userName , $userId , $now , $time);

            print_r($res);
        }elseif ($now > $zero && $now < $lastTime){

            $del = new \Home\Model\LunchModel('signup');
            $res = $del -> Del($userName , $userId , $now , $time);

            print_r($res);
        }

    }

    public function aaa()
    {

        $userName = "赵家宽";
        $userId = 'manager2651';
        $now = '1521446538';
        $time = '2018-3-19 16:2:18';

        $db = D('signup');

        $del = $db -> order('sign_time desc') -> limit(1) -> find();

        if($del['user'] == $userName){

            $del = $db -> order('sign_time desc') -> limit(1)-> delete();

            if($del){

                $zero = D('user');

                $change = $zero -> where(array(['userName' => $userName] , ['userId' => $userId])) -> save(['status' => 0]);


                dump($change);

                if($change){

                    $db = D('log_record');

                    $map = array(
                        'user' => $userName,
                        'userId' => $userId,
                        'operate' => 0,
                        'update_time' => $now,
                        'time' => $time,
                    );

                    $add = $db -> fetchSql(true) -> add($map);

                    dump($add);

                    if($add){
                        echo 1;
                    }else{
                        echo 0;
                    }
                }

            }

        }else{
            echo 2;
        }
    }

}

?>