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
        $show = $db -> field('userName , avatar') -> group('id') -> select();
        $this -> ajaxReturn($show);

    }


    public function history(){

        $db = M('signup');
        $userId = I('param.userId');
        $show = $db -> table('signup S') ->
        join('user U on U.userId = S.userId') ->
        where(['userId' => $userId]) -> field('userName , avatar , time') -> group('id desc') -> fetchSql() -> select();
        dump($show);
        #还要遍历、分页
    }
}