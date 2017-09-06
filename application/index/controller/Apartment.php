<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class Apartment extends BaseController {
	
	public function index() {
		$request = Request::instance();
		$logs = model('logs');
		
		$_ip = $request->ip();
		$_url = $request->url(true);
		
		$logs->log($_ip, 0, 'apartment', $_url, '/apartment');
		
		$m_alert = model('alert');
		$alert = $m_alert->getAlert('apartment');
		$button = $m_alert->getButtonStatus('apartment');
		
		$this->assign([
				'alert' => $alert,
				'button' => $button,
				'title' => '山东理工大学2017级公寓查询',
				'footer_extern_link' => [
						'href' => url('index/location/location'),
						'title' => '查看地图',
						'icon' => 'glyphicon glyphicon-send',
				],
		]);
		return $this->fetch();
	}
}
