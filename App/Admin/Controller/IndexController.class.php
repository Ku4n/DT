<?php
/**
 * Created by PhpStorm.
 * User: 赵家宽
 * Date: 2018/6/13
 * Time: 9:43
 */

namespace Admin\Controller;

use Think\Controller;

class IndexController extends Controller{

    public function Index(){

        dump(123);
    }


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

    public function login(){

        $db = M('dt_appid') ->find();
        $corpid = $db['corpid'];
        $ssosecret = $db['ssosecret'];

        $url = 'https://oapi.dingtalk.com/sso/gettoken?corpid='.$corpid.'&corpsecret='.$ssosecret.'';
        $data = json_decode(self::curl_get($url) , true);

        //dump($data);
        if($data['errcode'] !== 0){
            return false;
        }else{
            $ding['access_token'] = $data['access_token'];
            $ding['corpId'] = $corpid;

            return $ding;
        }

    }

    public function get_code(){

        $get_token = self::login();
        $code = $get_token['code'];
        $access_token = $get_token['access_token'];
        $url = 'https://oapi.dingtalk.com/user/getuserinfo?access_token='.$access_token.'&code='.$code.'';
        $data = json_decode(self::curl_get($url),true);
        if($data['errcode'] !== 0){
            return false;
        }else{
            $ding['errcode'] = $data['errcode'];
            $ding['access_token'] = $data['access_token'];

            return $ding;
        }
    }

    public function loginin(){

        $deft = self::get_code();

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
}