<?php
namespace Home\Controller;
use MongoDB\Driver\Server;
use Think\Controller;

/*header('content-type:application:json;charset=utf8');
header('Access-Control-Allow-Origin:*');   // 指定允许其他域名访问
header('Access-Control-Allow-Headers:x-requested-with,content-type');// 响应头设置*/


class IndexController extends CommonController {

    public function index(){

        echo 12;// 添加一个链接 ， 点击链接再次登录获取code
    }


    /**
     * Send a POST requst using cURL
     * @param string $url to request
     * @param array $post values to send
     * @param array $options for cURL
     * @return string
     */
    public function curl_post($url, array $post = NULL, array $options = array())
    {

        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_POSTFIELDS => http_build_query($post)
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if( ! $result = curl_exec($ch))
        {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    /**
     * Send a GET requst using cURL
     * @param string $url to request
     * @param array $get values to send
     * @param array $options for cURL
     * @return string
     */
    public function curl_get($url, array $get = NULL, array $options = array())
    {


        /*header("Access-Control-Allow-Origin: *");*/
        $defaults = array(
            CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if( ! $result = curl_exec($ch))
        {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }



    public function get_access_new() // 生成并返回access_token
    {

        $ding = M('dt_appid') -> find();

        $corpId = $ding['corpid'];
        $corpsecret = $ding['corpsecret'];

        if($corpId !== null || $corpsecret !== null){
            $token_url = 'https://oapi.dingtalk.com/gettoken?corpid='.$ding['corpid'].'&corpsecret='.$ding['corpsecret'];
            $data = json_decode(self::curl_get($token_url) , true);
            if($data['errmsg'] !== 'ok'){
                return false;
            }else{
                $ding['time'] = $data['expires_in'];
                $ding['access_token'] = $data['access_token'];


                # dump($data);
                return $ding;
            }
        }
    }

    public function get_ticket_new() // 生成并返回ticket
    {

        $token = self::get_access_new();
        $access_token = $token['access_token'];
        $ticket_url = 'https://oapi.dingtalk.com/get_jsapi_ticket?access_token='.$access_token.'';
        $data = json_decode(self::curl_get($ticket_url) , true);

        if($data['errmsg'] !== 'ok'){
            return false;
        }else{
            $ding['ticket'] = $data['ticket'];
            $ding['ticket_time'] = $data['expires_in'];

            # dump($data);
            return $ding;
        }

    }

    public function noncStr() // 生成随机数
    {

        $allChar = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $ding['noncStr'] = '';
        $randStr = str_shuffle($allChar);//打乱字符串
        $ding['noncStr'] = substr($randStr , mt_rand(0,strlen($randStr)) - 1,32 );

        $noncStr = $ding['noncStr'];

        return $noncStr;
        //var_dump($noncStr);
    }


    public function jsApi() // 签名
    {

        $get_access = self::get_access_new();
        $get_ticket = self::get_ticket_new();
        $noncStr = self::noncStr();
        $url = $get_access['url'];

        $ticket = $get_ticket['ticket'];
        $time = strtotime('now');

        $dd = 'jsapi_ticket=' . $ticket .'&noncestr=' . $noncStr .'&timestamp=' . $time .'&url=' . $url;

        $signature = sha1($dd);


        $page = array(
            'signature' => $signature,
            'time' => $time,
            'noncStr' => $noncStr,
        );

        # dump($dd);
        # dump($signature);
        return $page;
    }



    public function sign() // 签名包需要获取到签名时的 nonceStr ， timeStamp 以及 corpid 和 agentid 并返回给前端
    {

        $corp = self::get_access_new();
        $page = self::jsApi();

        $time = $page['time'];
        $nonceStr = $page['noncStr'];
        $signature = $page['signature'];

        $corpId = $corp['corpid'];
        $agentId = $corp['agentid'];

        $string = array(
            'agentid' => $agentId,
            'corpid' => $corpId,
            'timeStamp' => $time,
            'noncestr' => $nonceStr,
            'signature' => $signature
        );

        //return $string;

        $this -> ajaxReturn($string , 'json');


    }

    public function get_user()
    {

        $userId = I('post.userid');
        $userName = I('post.userName');
        $avater = I('post.avater');

        $db = M('user');
        $user = $db -> where(['userId' => $userId]) -> fetchSql(true) -> select();

        $map = array(
            'userId' => $userId,
            'userName' => $userName,
            'avater' => $avater
        );

        $this -> ajaxReturn($map);

/*        if($user['userId'] !== null){
            $json['msg'] = '登录成功！';
            $json['code'] = '0000';
            $this -> ajaxReturn($json );

        }else{
            $map = array(
                'userId' => $userId,
                'userName' => $userName,
                'avater' => $avater
            );

            $db -> add($map);
            if($db == true){
                $json['msg'] = '登录成功！';
                $json['code'] = '0000';
                $this -> ajaxReturn($json );
            }else{
                $json['msg'] = '写入信息失败！';
                $json['code'] = '0001';
                $this -> ajaxReturn($json );
            }

        }*/
    }


    public function login()
    {

        $userId = 'manager2651';
        $userName = "赵家宽";


        $check = new \Home\Model\IndexModel('user');
        $res = $check -> login($userId);

        // print_r($res);

        if($res == true){
            $json['msg'] = '登陆成功！，您今天未报名！';
            $json['status'] = '0';
            $json['code'] = 'true';
            $this -> ajaxReturn($json);
        }elseif ($res == 0){
            $json['msg'] = '登陆成功！，但程序有误';
            $json['status'] = '0';
            $json['code'] = '0000';
            $this -> ajaxReturn($json);
        }elseif ($res == 1){
            $json['msg'] = '提交信息有误！';
            $json['status'] = '';
            $json['code'] = '0001';
            $this -> ajaxReturn($json);
        }elseif ($res == 2){
            $json['msg'] = '登陆成功！，您今天已报名！';
            $json['status'] = '1';
            $json['code'] = 'true';
            $this -> ajaxReturn($json);
        }elseif ($res == false){
            $json['msg'] = '不在报名时间段内！';
            $json['status'] = '2';
            $json['code'] = 'true';
            $this -> ajaxReturn($json);
        }
    }




}