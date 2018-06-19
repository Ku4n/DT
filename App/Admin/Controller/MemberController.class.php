<?php
/**
 * Created by PhpStorm.
 * User: 赵家宽
 * Date: 2018/6/18
 * Time: 17:52
 */

namespace Admin\Controller;

use Think\Controller;

class MemberController extends Controller{


    public function index(){
        echo 123;
    }

    public function member(){

        $db = M('user');
        $show = $db -> field('userId , userName , avatar') -> group('id') -> select();
        $this -> ajaxReturn($show);

    }


    public function history(){

        $db = M('signup');
        $userId = I('param.userId');
        $userId = 'manager2651';
        $show = $db -> table('signup S') ->
        join('user U on U.userId = S.userId') ->
        where(['S.userId' => $userId]) -> field('S.id , userName , avatar , time') -> group('S.id desc') -> select();
        dump($show);
        foreach($show as $key => $val){#还要遍历、分页

        }
    }


    public function del(){

        $db = M('log_record');
        $signId = I('param.id');
        $userId = I('param.userId');

        #$signId = 3;
        #$userId = 'manager2651';

        if ($signId && $userId){

            $time = date('Y-m-d H:i:s');
            $now = strtotime($time);
            $map = array(
                'userId' => $userId,//这里要改成登录的用户（先保留一下
                'operate' => 4,
                'signId' =>$signId,
                'update_time' => $now,
                'time' => $time,
            );

            $markdown = $db -> table('log_record') -> add($map);
            if($markdown == true){
                $datebase = M('signup');
                $change = $datebase -> table('signup') -> where(['id' => $userId])-> save(['del' => true]);
                $this -> ajaxReturn(true);
            }else{
                return false;
            }

        }else{
            return false;//找不到删除的id及userId
        }
    }


    public function xx(){

        $db = M('signup');
        $change = $db -> where(['id' => 3])-> fetchSql() -> save(['del' => false]);
        dump($change);
    }
}