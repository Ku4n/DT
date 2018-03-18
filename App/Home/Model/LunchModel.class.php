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
            'uesr' => $userName,
            'userId' => $userId,
            'sign_time' => $now,
            'time' => $time,
        );

        $add = $map -> add($data);

        if ($add) {
            $db = D('user');
            $update = $db -> where(['userId' => $userId]) -> select();

            if ($update['user'] = $userName) {
                $update['status'] = 1;

                $change = $db -> save($update);

                if ($change) {
                    $record = M('log_record');

                    $operate = 1;

                    $map = array(
                        'user' => $userName,
                        'userId' => $userId,
                        'operate' => $operate,
                        'update_time' => $now,
                        'time' => $time
                    );

                    $log = $record->add($map);

                    if ($log) {
                        return true;
                    } else {
                        return 3;
                    }
                } else {
                    return 2;
                }


            }
        } else {
            return 1;
        }
    }


    public function Del($userName, $userId, $now, $time)// userName 报名人姓名 , userId 报名人ID , now 当前时间戳 , time date格式时间
    {


    }


}

?>