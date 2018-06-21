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

        if(isset($_GET['p'])){
            $p = I('get.p');
        }else{
            $p = 0;
        }
        $b = 10;//每页请求数
        $userName = I('param.userName');
        if($userName){
            $map['U.userName'] = array('like', '%' . $userName . '%');
        }
        $count = $db -> where($map) -> count();

        if($p>0){
            $list = $db -> field('id , userName , avatar') ->
            where($map) -> page($p,$b) -> select();
        }else{
            $list = $db -> field('id , userName , avatar') ->
            where($map) -> select();
        }

        $json['list'] = $list;
        $json['count'] = $count;
        $json['detail'] = '个人';
        $this -> ajaxReturn($json);
    }


    public function history(){

        $db = M('signup');
        $userId = I('param.userId');
        $userId = 'manager2652';
        $show = $db -> table('signup S') ->
        join('user U on U.userId = S.userId') ->
        where(['S.userId' => $userId]) -> field('S.id , userName , avatar , time') -> where(['del' => false]) -> group('S.id desc') -> select();

        if(isset($_GET['p'])){
            $p = I('get.p');
        }else{
            $p = 0;
        }
        $b = 10;//每页请求数
        $db = M('signup');
        $userName = I('param.userName');
        if($userName){
            $map['U.userName'] = array('like', '%' . $userName . '%');
        }
        $count = $db ->table('signup S') ->
        join('user U on U.userId = S.userId') -> where($map) -> count();

        if($p>0){
            $list = $db -> table('signup S') ->
            join('user U on U.userId = S.userId') ->
            field('S.id , U.userName , U.avatar , S.time') ->
            where($map) -> page($p,$b) -> select();
        }else{
            $list = $db -> table('signup S') ->
            join('user U on U.userId = S.userId') ->
            field('S.id , U.userName , U.avatar , S.time') ->
            where($map) -> select();
        }

        $json['list'] = $list;
        $json['count'] = $count;
        $json['detail'] = '历史';
        $this -> ajaxReturn($json);

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
                'signId' => $signId,
                'update_time' => $now,
                'time' => $time,
            );

            $markdown = $db -> table('log_record') -> add($map);
            if($markdown == true){
                $datebase = M('signup');
                $change = $datebase -> table('signup') -> where(['id' => $userId])-> save(['del' => true]);
                if($change == true){
                    $db = M('user');
                    $zero = $db -> where(['userId' => $userId]) -> save(['status' => 0]);
                    if($zero == true){
                        $this -> ajaxReturn(true);
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }

        }else{
            return false;//找不到删除的id及userId
        }
    }


    public function xx(){

        $db = M('signup');
        $userId = 'manager2652';
        $where = $db -> where(['userId' => $userId]) -> group('id desc') -> limit('1') -> find();
        dump($where['id']);
    }
}