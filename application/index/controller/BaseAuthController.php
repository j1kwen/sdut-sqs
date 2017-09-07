<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\index\model\Auth;

/**
 * 验证控制器基类
 * @author Shannon
 *
 */
class BaseAuthController extends BaseController{
	
	protected $_auth = false;
	
	/**
	 * 控制器初始化，判断用户是否登录或登录是否过期
	 * {@inheritDoc}
	 * @see \think\Controller::_initialize()
	 */
	public function _initialize() {
		parent::_initialize();
		$this->_auth = Auth::login();
		if(!$this->_auth) {
			$request = Request::instance();
			$_ctrl = $request->controller();
			$_action = $request->action();
			$_url = 'index/'.$_ctrl.'/'.$_action;
			if($request->isAjax()) {
				$this->error('登录信息貌似已经过期，请刷新页面后重新登录！');
			} else {
				Session::set('redir', true);
				Session::set('redir_url', $_url);
				$this->redirect('index/index/login');
			}
		}
	}
}