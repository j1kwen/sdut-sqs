<?php

namespace app\index\controller;

use app\index\controller\BaseController;
use think\Request;

class Student extends BaseController {
	
	public function item() {
		$request = Request::instance();
		if($request->isAjax()) {
			$_name = $request->param('name','');
			if(empty($_name)) {
				$this->error('参数不完整');
			}
			$stu = model('student');
			$list = $stu->getStudentByName($_name);
			
			$logs = model('logs');
			
			$_ip = $request->ip();
			$_url = $request->url(true);
			
			$logs->log($_ip, 1, $_name, $_url, 'name='.$_name);
			
			foreach ($list as $item) {
				$room = $item['dormitory'];
				$r_name = getRoomName($room);
				$r_id = getRoomId($room);
				$key = strchr($room, 'H', true);
				if($key == '13') {
					$item['r_name'] = $item['r_name'].$r_id;
				} else {					
					$item['r_name'] = $r_name;
				}
			}
			
			$this->assign([
					'list' => $list,
					'_name' => $_name,
			]);
			return $this->fetch();
		} else {
			$this->error();
		}
	}
	
	public function roommate() {
		$request = Request::instance();
		if($request->isAjax()) {
			$_room = $request->param('room', '');
			$_name = $request->param('name', '');
			if(empty($_room) || empty($_name)) {
				$this->error('参数不完整');
			}
			$stu = model('student');
			$list = $stu->getRoommate($_room);
			
			$logs = model('logs');
			
			$_ip = $request->ip();
			$_url = $request->url(true);
			
			$logs->log($_ip, 2, $_room.'['.$_name.']', $_url, 'room='.$_room.'&name='.$_name);
			
			$r_name = getRoomName($_room);
			$r_id = getRoomId($_room);
			$key = strchr($_room, 'H', true);
			if($key == '13' && !empty($list)) {
				$r_name = $list[0]['r_name'].$r_id;
			}
			
			$this->assign([
					'list' => $list,
					'room' => $_room,
					'r_name' => $r_name,
					'room_size' => count($list),
			]);
			return $this->fetch();
		} else {
			$this->error();
		}
	}
}