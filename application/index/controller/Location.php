<?php

namespace app\index\controller;

use app\index\controller\BaseController;
use think\Request;

class Location extends BaseController {

	public function location() {
		$request = Request::instance();
		
		$room_name = config('room_name');
		
		$_dorm = $request->param("dormitory", '');
		
		$logs = model('logs');
			
		$_ip = $request->ip();
		$_url = $request->url(true);
			
		$logs->log($_ip, 4, strtoupper($_dorm), $_url, 'dormitory='.$_dorm);
		
		$dorm_id = getDormitoryId(strtoupper($_dorm));
		$dorm_name = '';
		if(isset($room_name[$dorm_id])) {
			$dorm_name = $room_name[$dorm_id];
		} else {
			$dorm_id = '';
		}
		
		$this->assign([
				'room_name' => $room_name,
				'split_line' => false,
				'dorm_id' => $dorm_id,
				'dorm_name' => $dorm_name,
		]);
		return $this->fetch();
	}
}