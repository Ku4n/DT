<?php
/**
 * Created by PhpStorm.
 * User: 赵家宽
 * Date: 2018/3/23
 * Time: 9:54
 */


namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        header("ACCESS-CONTROL-ALLOW-ORIGIN:*");
        header('Access-Control-Allow-Methods: POST, GET,OPTIONS');
        header('Access-Control-Allow-Headers: Authorisation,Content-Type,Accept');

    }

}
?>