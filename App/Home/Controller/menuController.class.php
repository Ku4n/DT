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

        // 记得添加accesstoken上的判定 返回值200？ 还是其他返回
        //如果存在其他 要否弹出提示

    }

}