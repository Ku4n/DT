<?php
/**
 * Created by PhpStorm.
 * User: 赵家宽
 * Date: 2018/3/15
 * Time: 22:05
 */

namespace Home\Model;

use Think\Model;


class LunchModel extends Model
{


    public function Add($userId, $now, $time){// userId 报名人ID , now 当前时间戳 , time date格式时间
        $db =M('signup');
        $data = array(
            'userId' => $userId,
            'sign_time' => $now,
            'time' => $time,
        );

        $add = $db -> add($data);

        if($add == true){

            $db = M('user');
            $update = $db -> where(['userId' => $userId]) -> save(['status' => 1]);

            if($update == true){

                $db = M('signup');
                $where = $db -> where(['userId' => $userId]) -> group('id desc') -> limit('1') -> find();
                $signId = $where['id'];
                $map = array(
                    'userId' => $userId,
                    'operate' => 1,
                    'signId' => $signId,
                    'update_time' => $now,
                    'time' => $time,
                    'operater' => $userId
                );

                $db = M('log_record');
                $log = $db -> table('log_record') -> add($map);

                if($log == true){
                    return true;
                }else{
                    return 0;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    public function Del($userId , $now , $time)// userName 报名人姓名 , userId 报名人ID , now 当前时间戳 , time date格式时间
    {

        $db = M('signup');
        $del = $db -> where(['userId' => $userId]) -> order('sign_time desc') -> limit(1) -> save(['del' => true]);

        if($del == true){
            $zero = M('user');
            $change = $zero -> where(array(['userId' => $userId])) -> save(['status' => 0]);
            if($change == true){

                $db = M('signup');
                $where = $db -> where(['userId' => $userId]) -> group('id desc') -> limit('1') -> find();
                $signId = $where['id'];
                $db = M('log_record');
                $map = array(
                    'userId' => $userId,
                    'operate' => 0,
                    'signId' => $signId,
                    'update_time' => $now,
                    'time' => $time,
                    'operater' => $userId
                );

                $add = $db -> add($map);

                if($add){
                    return true;
                }else{
                    return 0;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }

    }
}

?>