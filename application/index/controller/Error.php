<?php
namespace app\index\controller;

use think\Controller;

/**
 * 默认错误控制器，访问不存在的页面时跳转至此
 * @author Shannon
 *
 */
class Error extends BaseController
{
    public function index()
    {
    	$this->error();
    }
}
