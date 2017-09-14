<?php

namespace app\index\controller;

use app\index\controller\BaseController;
use think\Request;

class Student extends BaseController {
	
	public function index() {
		$request = Request::instance();
		$logs = model('logs');
		
		$_ip = $request->ip();
		$_url = $request->url(true);
		
		$logs->log($_ip, 0, 'student', $_url, '/student');
		
		$m_alert = model('alert');
		$m_button = model('button');
		$alert = $m_alert->getAlert('student');
		$button = $m_button->getButton('student');
		
		$this->assign([
				'alert' => $alert,
				'button' => $button,
				'title' => '学生学号姓名查询',
				'footer_extra_link' => [
						'href' => 'http://api.dogest.cn/grade/student.html',
						'title' => 'API文档',
				],
		]);
		return $this->fetch();
	}
	
	public function info() {
		$request = Request::instance();
		if($request->isAjax()) {
			$_name = $request->param('name','');
			$_id = $request->param('id','');
			$_page = $request->param('page','');
			$_size = $request->param('size','');
			if(empty($_name) && empty($_id)) {
				$this->error('参数不完整');
			}
			
			$j_data = null;
			$url='http://api.dogest.cn/grade/grade/info?id='.$_id.'&name='.$_name.'&page='.$_page.'&size='.$_size;
			//$url='http://localhost:8080/grade/info?id='.$_id.'&name='.$_name.'&page='.$_page.'&size='.$_size;
			
			$html = file_get_contents($url);
			$j_data = json_decode($html);
			
			$code = $j_data->code;
			$message = $j_data->message;
			$status = $j_data->status;
			
			$list = [];
			$data = null;
			$size = null;
			$page = null;
			$pageCnt = null;
			if($code == 0) {
				$data = $j_data->data;

				$list = $data->list;
				$size = $data->size;
				$page = $data->page;
				$pageCnt = ceil(floatval($data->total) / floatval($size));
			}
			
			$logs = model('logs');
			
			$_ip = $request->ip();
			$_url = $request->url(true);
			
			$logs->log($_ip, 7, $_id.'['.$_name.']', $_url, 'id='.$_id.'&name='.$_name);
			
			$this->assign([
					'code' => $code,
					'message' => $message,
					'status' => $status,
					'list' => $list,
					'data' => $data,
					'page' => $page,
					'size' => $size,
					'pageCnt' => $pageCnt,
					'id' => $_id,
					'name' => $_name,
			]);
			return $this->fetch();
		} else {
			$this->error();
		}
	}
	
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