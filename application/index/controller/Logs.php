<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class Logs extends BaseController {
	
	public function index() {
		$request = Request::instance();
		
		$_auth = $request->param('auth', '');
		
		if($_auth != '19980604') {
			$this->error('tan 90°');
		}
		
		$this->assign([
				'title' => '日志查看',
		]);
		return $this->fetch();
	}
	
	public function item() {
		$request = Request::instance();
		if($request->isAjax()) {
			$_page = (int)$request->param('page', '1');
			$_size = (int)$request->param('size', '20');
			$_type = (int)$request->param('type', '0');
			if(empty($_page) || empty($_size)) {
				$this->error('参数不完整');
			}
			$logs = model('logs');
			$list = $logs->item($_type, $_page, $_size);
			$listCnt = $logs->getCount();
			$this->assign([
					'list' => $list,
					'type' => $_type,
					'cnt' => $listCnt,
					'page' => $_page,
			]);
			return $this->fetch();
		} else {
			$this->error('tan 90°');
		}
	}
}