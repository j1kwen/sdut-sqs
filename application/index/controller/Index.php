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
				'title' => '山东理工大学2017级公寓查询',
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
