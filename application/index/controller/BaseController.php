<?php
namespace app\index\controller;

use think\Controller;

/**
 * 控制器基类
 * @author Shannon
 *
 */
class BaseController extends Controller {
    
	protected $_auth = false;
	
	/**
	 * 控制器初始化，判断用户是否登录或登录是否过期
	 * {@inheritDoc}
	 * @see \think\Controller::_initialize()
	 */
    public function _initialize() {
    	parent::_initialize();
    }
}
