<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class Logs extends BaseAuthController {
	
	public function index() {
		$request = Request::instance();
		
		$_auth = $request->param('auth', '');
		
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
			$pageCnt = 1;
			foreach($listCnt as $lc) {
				if($lc['type'] == $_type) {
					$pageCnt = $lc['val'];
					$pageCnt = ceil($pageCnt / $_size);
					break;
				}
			}
			$this->assign([
					'list' => $list,
					'type' => $_type,
					'cnt' => $listCnt,
					'pageCnt' => $pageCnt,
					'page' => $_page,
					'size' => $_size,
			]);
			return $this->fetch();
		} else {
			$this->error('tan 90°');
		}
	}
}