<?php
/**
 * Created by PhpStorm.
 * User: 赵家宽
 * Date: 2018/6/15
 * Time: 16:53
 */


namespace Admin\Controller;

use Think\Controller;


class RegisterController extends Controller{


    public function index(){

        echo 123;
    }

    public function all_sign(){//显示当天

        $db = M('signup');
        $start_time = strtotime(I('param.start_time'));
        $end_time = strtotime(I('param.end_time'));

        $map['sign_time'] = array(array('EGT' , "$start_time") , array('LT' , "$end_time"));

        if($start_time && $end_time){
            $lunch = $db -> table('signup S') ->
            join('user U on U.userId = S.userId') ->
            field('U.userName , S.time') -> where($map) -> group('S.id') -> select();

            $this -> ajaxReturn($lunch);

        }else{
            return false;
        }
    }


    public function person_sign(){//需要分页

        $db = M('signup');
        $userId = I('param.userId');
        #$userId = 'manager2651';

        $person = $db ->  table('signup S') ->
        join('user U on U.userId = S.userId') ->
        field('U.userName , S.time') -> where(['S.userId' => $userId]) -> group('S.id desc') -> select();

        dump($person);
    }

    public function add(){

        $db = M('signup');

    }


}