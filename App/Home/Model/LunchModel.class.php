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

    public function Add($userId, $now, $time)// userId 报名人ID , now 当前时间戳 , time date格式时间
    {

        $map = M('signup');

        $data = array(
            'userId' => $userId,
            'sign_time' => $now,
            'time' => $time,
        );

        $add = $map -> add($data);

        if ($add == true) {
            $db = M('user');
            $update = $db -> where(array(['userId' => $userId] )) -> save(['status' => 1]);

            if ($update == true) {
                $record = M('log_record');

                $map = array(
                    'userId' => $userId,
                    'operate' => 1,
                    'update_time' => $now,
                    'time' => $time
                );

                $log = $record -> add($map);

                if ($log == true) {
                    echo true;
                } else {
                    return 0;
                }
            } else {
                return 1;
            }
        } else {
            return 2;
        }

    }


    public function Del($userId , $now , $time)// userName 报名人姓名 , userId 报名人ID , now 当前时间戳 , time date格式时间
    {

        $db = M('signup');
        $del = $db -> where(['userId' => $userId]) -> order('sign_time desc') -> limit(1) -> delete();

        if($del == true){
            $zero = M('user');
            $change = $zero -> where(array(['userId' => $userId])) -> save(['status' => 0]);
            if($change == true){

                $db = M('log_record');
                $map = array(
                    'userId' => $userId,
                    'operate' => 0,
                    'update_time' => $now,
                    'time' => $time,
                );

                $add = $db -> add($map);

                if($add){
                    return true;
                }else{
                    return 0;
                }
            }else{
                return 1;
            }
        }else{
            return 2;
        }

    }

}

?>