<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class System extends BaseAuthController {
	
	public function index() {
		//$this->redirect('Apartment/index');
		$request = Request::instance();
		$logs = model('logs');
		
		$_ip = $request->ip();
		$_url = $request->url(true);
		
		$logs->log($_ip, 0, 'system', $_url, '/system');
		
		$m_button = model('button');
		$models = $m_button->getModel();
		
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
			$m_button = model('button');
			$alert = $m_alert->getAlert($_model, 1);
			$button = $m_button->getButton($_model);
			
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
	
	public function button() {
		$request = Request::instance();
		if($request->isAjax()) {
			$_model = $request->param('model','');
			$_text = $request->param('text','');
			$_dis_text = $request->param('dis_text','');
			$_color = $request->param('color','');
			$_enable = $request->param('enable','');
			if(empty($_model) || empty($_text) || empty($_dis_text) || empty($_color)) {
				return json([
						'success' => false,
						'msg' => 'param error',
				]);
			}
			try {				
				$m_button = model('button');
				$m_button->modifyButton($_model, $_text, $_dis_text, $_color, $_enable);
				return json([
						'success' => true,
						'msg' => 'success',
				]);
			} catch (\think\Exception $e) {
				return json([
						'success' => false,
						'msg' => $e->getMessage(),
				]);
			}
		} else {
			$this->error();
		}
	}
	
	public function modify() {
		$request = Request::instance();
		if($request->isAjax()) {
			
			$_id = $request->param('id','');
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
