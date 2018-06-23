<?php
/**
 * Created by PhpStorm.
 * User: 赵家宽
 * Date: 2018/6/18
 * Time: 17:52
 */

namespace Admin\Controller;

use Think\Controller;

class MemberController extends CommonController{


    public function index(){
        echo 123;
    }

    #全部成员
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


    #个人历史
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


    #个人页，删除历史记录
    public function Del(){

        $db = M('log_record');
        $signId = I('param.id');
        $userId = I('param.userId');
        $operater = I('param.operater');
        #$signId = 3;
        #$userId = 'manager2651';
        #$operater = 'manager2655';

        if ($signId && $userId){

            $time = date('Y-m-d H:i:s');
            $now = strtotime($time);
            $map = array(
                'userId' => $userId,//这里要改成登录的用户（先保留一下
                'operate' => 4,
                'signId' => $signId,
                'update_time' => $now,
                'time' => $time,
                'operater' => $operater
            );

            $markdown = $db -> table('log_record') -> add($map);
            if($markdown == true) {
                $datebase = M('signup');
                $change = $datebase -> table('signup') -> where(['id' => $signId]) -> save(['del' => true]);
                if($change == true){
                    $json['detail'] = '删除成功！';
                    $json['code'] = 'success';
                    $this->ajaxReturn($json);
            }else{
                    $json['detail'] = '删除失败，请联系管理员！';
                    $json['code'] = 'fail';
                    $this->ajaxReturn($json);
                }
            }else{
                $json['detail'] = '删除失败！';
                $json['code'] = 'fail';
                $this->ajaxReturn($json);
            }

        }else{
            $json['detail'] = '参数不足！';
            $json['code'] = 'fail';
            $this->ajaxReturn($json);
        }
    }


    #应急添加
    public function Add(){
        $db = M('signup');
        #$userId = I('param.userId');
        #$sign_time = I('param.time');//前端返回选择日期(时间戳格式)，添加当天的报名次数
        #$operater = I('param.operater');
        $userId = 'manager2651';
        $sign_time = '1529632831';
        $time = date('Y-m-d H:i:s' , $sign_time);
        $operater = 'manager2655';

        $map = array(
            'userId' => $userId,
            'sign_time' => $sign_time,
            'time' => $time,
            'supply' => true
        );

        $add = $db -> add($map);

        if($add == true){

            $where = $db -> where(['userId' => $userId]) -> group('id desc') -> find();
            $signId = $where['id'];

            $db = M('log_record');
            $map = array(
                'userId' => $userId,
                'operate' => 3,
                'signId' => $signId,
                'time' => $time,
                'update_time' => $sign_time,
                'operater' => $operater
            );

            $add = $db -> add($map);

            if($add == true){
                $json['detail'] = '添加成功';
                $json['code'] = 'success';
                $this -> ajaxReturn($json);
            }else{
                $json['detail'] = '添加失败，请联系管理员';
                $json['code'] = 'fail';
                $this -> ajaxReturn($json);
            }
        }else{
            $json['detail'] = '添加失败';
            $json['code'] = 'fail';
            $this -> ajaxReturn($json);
        }

    }


    public function xx(){

        $db = M('signup');
        #$userId = I('param.userId');
        #$sign_time = I('param.time');//前端返回选择日期(时间戳格式)，添加当天的报名次数
        #$operater = I('param.operater');
        $userId = 'manager2651';
        $sign_time = '1529632831';
        $time = date('Y-m-d H:i:s' , $sign_time);
        $operater = 'manager2655';

        $map = array(
            'userId' => $userId,
            'sign_time' => $sign_time,
            'time' => $time,
            'supply' => true
        );

        $add = $db -> add($map);

        if($add == true){

            $where = $db -> where(['userId' => $userId]) -> group('id desc') -> find();
            $signId = $where['id'];

            $db = M('log_record');
            $map = array(
                'userId' => $userId,
                'operate' => 3,
                'signId' => $signId,
                'time' => $time,
                'update_time' => $sign_time,
                'operater' => $operater
            );

            $add = $db -> add($map);

            if($add == true){
                $json['detail'] = '添加成功';
                $json['code'] = 'success';
                $this -> ajaxReturn($json);
            }else{
                $json['detail'] = '添加失败，请联系管理员';
                $json['code'] = 'fail';
                $this -> ajaxReturn($json);
            }
        }else{
            $json['detail'] = '添加失败';
            $json['code'] = 'fail';
            $this -> ajaxReturn($json);
        }
    }
}