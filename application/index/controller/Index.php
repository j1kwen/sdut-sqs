<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class Index extends BaseController {
	
    public function index() {
    	$request = Request::instance();
    	$logs = model('logs');
    	
    	$_ip = $request->ip();
    	$_url = $request->url(true);
    	
    	$logs->log($_ip, 0, 'index', $_url, '/index');
    	
    	$this->assign([
				'title' => '山东理工大学学生信息查询系统  - SDUT SQS',
    			'footer_extern_link' => [
    					'title' => '联系作者',
    					'href' => 'mailto:763461297@qq.com?subject=【学生查询系统】问题反馈',
    					'icon' => 'glyphicon glyphicon-envelope',
    					'target' => '_self',
    			],
    	]);
    	return $this->fetch();
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
