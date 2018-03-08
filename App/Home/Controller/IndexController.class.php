<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {

/*    public function index()
    {
        header('location:https://www.sport147.cn/');die;
    }*/

    public function index(){
        //header('location:Home/Index/sign');die;
        //header('location:./View/Index/index.html');
        $this -> display('index');

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



    public function get_access_new(){
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

                return $ding;
            }
        }
    }

    public function get_ticket_new(){
        $token = self::get_access_new();
        $access_token = $token['access_token'];
        $ticket_url = 'https://oapi.dingtalk.com/get_jsapi_ticket?access_token='.$access_token.'';
        $data = json_decode(self::curl_get($ticket_url) , true);

        if($data['errmsg'] !== 'ok'){
            return false;
        }else{
            $ding['ticket'] = $data['ticket'];
            $ding['ticket_time'] = $data['expires_in'];

            return $ding;
        }

    }

    public function noncStr(){
        $allChar = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $ding['noncStr'] = '';
        $randStr = str_shuffle($allChar);//打乱字符串
        for($i = 0 ; $i <= 16 ; $i++){
            $ding['noncStr'] = substr($randStr , mt_rand(0,strlen($randStr)) - 1,32 );
        }

        $noncStr = $ding['noncStr'];

        return $noncStr;
        //var_dump($noncStr);
    }


    public function jsApi(){
        $get_access = self::get_access_new();
        $get_ticket = self::get_ticket_new();
        $noncStr = self::noncStr();
        $url = $get_access['url'];

        $ticket = $get_ticket['ticket'];
        $time = strtotime('now');

        $dd = 'jsapi_ticket=' . $ticket .'&noncestr=' . $noncStr .'&timestamp=' . $time .'&url=' . $url;
        $signature = sha1($dd);

        return $signature;
    }


    public function sign(){

        $signature = self::jsApi();
        $corp = self::get_access_new();
        $time = strtotime('now');
        $noncStr = self::noncStr();


        $corpId = $corp['corpid'];
        $agentId = $corp['agentid'];

        $string = array(
            'agentid' => $agentId,
            'corpid' => $corpId,
            'timeStamp' => $time,
            'nonceStr' => $noncStr,
            'signature' => $signature
        );

        //return $string;

        $this -> ajaxReturn($string);
    }


}