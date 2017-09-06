<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class System extends BaseController {
	
	public function index() {
		//$this->redirect('Apartment/index');
		$request = Request::instance();
		$logs = model('logs');
		
		$_ip = $request->ip();
		$_url = $request->url(true);
		
		$logs->log($_ip, 0, 'system', $_url, '/system');
		
		$m_alert = model('alert');
		$models = $m_alert->getModel();
		
		$this->assign([
				'models' => $models,
				'title' => '后台管理  - SDUT SQS',
		]);
		return $this->fetch();
	}
	
	public function item() {
		$request = Request::instance();
		if($request->isAjax()) {			
			$_model = $request->param('model','');
			if(empty($_model)) {
				$this->error();
			}
			$m_alert = model('alert');
			$alert = $m_alert->getAlert($_model, 1);
			$button = $m_alert->getButtonStatus($_model);
			
			$this->assign([
					'alert' => $alert,
					'button' => $button,
					'model' => $_model,
			]);
			return $this->fetch();
		} else {
			$this->error();
		}
		
	}
}
