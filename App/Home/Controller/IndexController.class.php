<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {

/*    public function index()
    {
        header('location:https://www.sport147.cn/');die;
    }*/

public function index(){
    $this -> display();
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


    public function get_access(){//$kw , $url
        $dd = M('dt_appid');
        $ding = $dd -> find();
        $current_time = date_timestamp_get(date_create());
        //$ding['timestamp'] = $current_time;

        $ding['timestamp'] = '1519802507';

        if (!$ding['access_token'] || $current_time >= $ding['expires']){
            $token_url = 'https://oapi.dingtalk.com/gettoken?corpid='.$ding['corpid'].'&corpsecret='.$ding['corpsecret'];
            $data = json_decode(self::curl_get($token_url) , true);
            $ding['access_token'] = $data['access_token'];
            //var_dump($ding['access_token']);
            $ticket_url = 'https://oapi.dingtalk.com/get_jsapi_ticket?access_token='.$ding['access_token'].'';
            //var_dump($ticket_url);
            $data = json_decode(self::curl_get($ticket_url) , true);
            $ding['jsapi_ticket'] = $data['ticket'];


            // noncStr 的随机生成
/*            $allChar = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $ding['noncStr'] = '';
            for($i = 0 ; $i <= 6 ; $i++){
                $ding['noncStr'] = substr($allChar , mt_rand(0,strlen($allChar)) - 1,3);
            }*/

            $ding['noncStr'] = 'ABd';
            // print_r($ding['jsapi_ticket']);

            $ding['url'] = 'https://sport147.cn';

/*            $string = array(
                'jsapi_ticket' => $ding['jsapi_ticket'],
                'noncstr' => $ding['noncStr'],
                'timestamp' => $ding['timestamp'],
                'url' => $ding['url']
            );

            ksort($string);
            $ding['signature'] = sha1(urldecode(http_build_query($string)));
*/

            $dd = 'jsapi_ticket=' . $ding['jsapi_ticket'] .'&noncestr=' . $ding['noncStr'] .'&timestamp=' . $ding['timestamp'] .'&url=' . $ding['url'];

            $ding['signature'] = sha1($dd);

/*            print_r($dd);
            echo '<br/>';
            var_dump($ding['signature']);
            echo '<br/>';*/


            $allChar = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $ding['noncStr'] = '';
            for($i = 0 ; $i <= 6 ; $i++) {
                $ding['noncStr'] = substr($allChar, mt_rand(0, strlen($allChar)) - 1, 3);
            }

            $ding['agentid'] = 161276226;

            $plain = 'url='. $ding['url'] .'&noncstr=' . $ding['noncStr'] . '&agentid=' . $ding['agentid'] . '&timestamp=' . $ding['timestamp'] . '&corpId=' . $ding['corpid'] .'&signature=' . $ding['signature'];

            var_dump($plain);

            $this -> ajaxReturn(htmlspecialchars($plain));



        }elseif(!$ding['jsapi_ticet'] || $current_time >= $ding['expires']){
            $ticket_url = 'https://oapi.dingtalk.com/get_jsapi_ticket?access_token='.$ding['access_token'].'';
            var_dump($ticket_url);
            $data = json_decode(self::https_request($ticket_url) , true);
            $ding['jsapi_ticket'] = $data['ticket'];
            var_dump($ding['jsapi_ticket']);
        }



    }


    public function ttt(){

        $url = 'https://sport147.cn?corpid=ding3d07a31c1957d50d35c2f4657eb6378f';
        $ding['url'] = htmlspecialchars_decode($url);

        $ding['jsapi_ticket'] = 'wDc7cK7RbPm4ovo7U9L5bSWq9CTNPLkTvJH27A14zGPcQIxJoru4pVOGFMfFtsUSVbjNGY64yPoeKqdmgSJFOj';
        $ding['noncStr'] = ABd;
        $ding['timestamp'] = 1519802507;

        //$dd = 'jsapi_ticket=' . $ding['jsapi_ticket'] .'&nonceStr=' . $ding['noncStr'] .'&timestamp=' . $ding['timestamp'] .'&&url=' . $ding['url'];

        $string = array(
            'jsapi_ticket' => $ding['jsapi_ticket'],
            'noncStr' => $ding['noncStr'],
            'timeStamp' => $ding['timestamp'],
            'url' => $ding['url']
        );

        $ding['signature'] = sha1(urldecode(http_build_query($string)));

        //print_r($ding['signature']);


    }


    public function user(){

        $map['name'] = 'zhao';
        $map['tel'] = '1371';

        $string = array(
            "name" => $map['name'],
            "tel" => $map['tel']
        );

        $this -> ajaxReturn($string);
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
        for($i = 0 ; $i <= 16 ; $i++){
            $ding['noncStr'] = substr($allChar , mt_rand(0,strlen($allChar)) - 1,3);
        }

        $noncStr = $ding['noncStr'];

        return $noncStr;
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