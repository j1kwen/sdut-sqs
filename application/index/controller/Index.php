<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\index\model\Auth;

class Index extends BaseController {
	
    public function index() {
    	//$this->redirect('Apartment/index');
    	$request = Request::instance();
    	$logs = model('logs');
    	
    	$_ip = $request->ip();
    	$_url = $request->url(true);
    	
    	$logs->log($_ip, 0, 'index', $_url, '/index');
    	
    	$this->assign([
				'title' => '山东理工大学学生信息查询系统  - SDUT SQS',
    			'footer_extra_link' => [
    					'title' => '联系作者',
    					'href' => 'mailto:10000@dogest.cn?subject=【学生查询系统】问题反馈',
    					'icon' => 'glyphicon glyphicon-envelope',
    					'target' => '_self',
    			],
    			'footer_extra_link2' => [
    					'href' => url('index/system/index'),
    					'icon' => 'glyphicon glyphicon-cog',
    					'title' => '后台管理',
    			],
    			'has_login' => Auth::login(),
    	]);
    	return $this->fetch();
    }
    
    public function login() {
    	
    	if (!Auth::login()) {
    		if(Session::has('redir') && Session::get('redir')) {
    			$_alert = '请先登录！';
    			Session::delete('redir');
    		}
    		$this->assign([
    				'title' => '后台用户登录 - SDUT SQS',
    				'alert' => isset($_alert) ? $_alert : null,
    		]);
    		return $this->fetch();
    	} else {
    		$_url = 'index/index/index';
    		$this->redirect($_url);
    	}
    }
    
    public function verify(Request $request) {
    	if($request->isAjax()) {
    		$_user = $request->param('user');
    		$_pwd = $request->param('password');
    		$_code = $request->param('captcha');
    		if (!empty($_user) && !empty($_pwd) && !empty($_code)) {
    			if(!captcha_check($_code)) {
    				// code error
    				return getAjaxResp("验证码错误！", false, -123);
    			}
    			$auth = new Auth();
    			if($auth->authUser($_user, $_pwd, $request->ip(0, true))) {
    				Auth::login($_user, $auth->getName($_user));
    				$_url = 'index/system/index';
    				if(Session::has('redir_url')) {
    					$_url = Session::get('redir_url');
    					Session::delete('redir_url');
    				}
    				return json([
    						'success' => true,
    						'url' => url($_url),
    						'msg' => 'success',
    				]);
    			} else {
    				return getAjaxResp("用户名或密码错误!");
    			}
    		} else {
    			return getAjaxResp("请输入完整信息!", false);
    		}
    	} else {
    		$this->error();
    	}
    }
    
    public function mmd() {
    	$request = Request::instance();
    	if($request->isAjax()) {
	    	$logs = model('logs');
	    	
	    	$_ip = $request->ip();
	    	$_url = $request->url(true);
			
			$_who = $request->param('who', '');
			$_to = '';
			if($_who == "me") {
				$_to = 'Shannon,';
			} else if($_who == "uk") {
				$_to = 'UK酱,';
			} else {
				$this->error();
			}
	    	
	    	$logs->log($_ip, 3, $_to.'mua~', $_url, '');
    		
    	} else {
    		$this->error();
    	}
    }
}
