<?php
/**
 * Created by PhpStorm.
 * User: 赵家宽
 * Date: 2018/1/31
 * Time: 14:35
 */
namespace Think\Controller;
use Think\Controller;


class menu extends Controller{

    public function menu()
    {
       $menu = M('signup');
        $this -> redirect();

       $this -> display(U('Home/View/Homepage'));
        $this -> assgin();
    }

    public function menulist()
    {
        $list = M('signup');


    }

}