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


    public function Del($userName, $userId, $now, $time)// userName 报名人姓名 , userId 报名人ID , now 当前时间戳 , time date格式时间
    {
        $db = D('signup');

        $del = $db -> where(array(['userId' == $userId])) -> find();

        dump($del);
    }


    public function test($userName , $userId , $now , $time)
    {
        $map = D('signup');

        $data = array(
            'uesr' => $userName,
            'userId' => $userId,
            'sign_time' => $now,
            'time' => $time,
        );

        $add = $map -> add($data);


    }



}

?>