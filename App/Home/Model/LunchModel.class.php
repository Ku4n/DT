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

    public function Add($userName, $userId, $now, $time)// userName 报名人姓名 , userId 报名人ID , now 当前时间戳 , time date格式时间
    {
        $map = D('signup');

        $data = array(
            'uesrName' => $userName,
            'userId' => $userId,
            'sign_time' => $now,
            'time' => $time,
        );

        $add = $map -> add($data);

        if ($add) {
            $db = D('user');

            $update = $db -> where(array(['userId' => $userId] , ['userName' => $userName])) -> fetchSql(true) -> save(['status' => 1]);

            // dump($update);

                if ($update) {
                    $record = M('log_record');

                    $operate = 1;

                    $map = array(
                        'user' => $userName,
                        'userId' => $userId,
                        'operate' => $operate,
                        'update_time' => $now,
                        'time' => $time
                    );

                    $log = $record -> fetchSql(true) -> add($map);

                    // dump($log);

                    if ($log) {
                        return 0;
                    } else {
                        return 3;
                    }
                } else {
                    return 2;
                }

            } else {
            return false;
        }

    }


    public function Del($userName , $userId , $now , $time)// userName 报名人姓名 , userId 报名人ID , now 当前时间戳 , time date格式时间
    {

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

        }else{
            return 3;
        }
    }

}

?>