<?php

namespace app\index\controller;

use app\index\controller\BaseController;
use think\Request;

class Energy extends BaseController {
	
	private static $build_list = [
			// west
			"west" => [
					["id" => "1nh", "text" => "1号公寓南楼",],
					["id" => "1bh", "text" => "1号公寓北楼",],
					["id" => "2nh", "text" => "2号公寓南楼",],
					["id" => "2bh", "text" => "2号公寓北楼",],
					["id" => "3nh", "text" => "3号公寓南楼",],
					["id" => "3bh", "text" => "3号公寓北楼",],
					["id" => "4nh", "text" => "4号公寓南楼",],
					["id" => "4bh", "text" => "4号公寓北楼",],
					["id" => "5h", "text" => "5号公寓",],
					["id" => "6h", "text" => "6号公寓",],
					["id" => "7h", "text" => "7号公寓",],
					["id" => "8h", "text" => "8号公寓",],
					["id" => "9h", "text" => "9号公寓",],
					["id" => "10h", "text" => "10号公寓",],
					["id" => "11h", "text" => "11号公寓",],
					["id" => "12h", "text" => "12号公寓",],
					["id" => "13nh", "text" => "13号公寓南楼",],
					["id" => "13bh", "text" => "13号公寓北楼",],
					["id" => "14h", "text" => "14号公寓",],
					["id" => "15h", "text" => "15号公寓",],
					["id" => "16h", "text" => "16号公寓",],
					["id" => "17h", "text" => "17号公寓",],
					["id" => "18h", "text" => "18号公寓",],
					["id" => "19h", "text" => "19号公寓",],
					["id" => "20h", "text" => "20号公寓",],
					["id" => "21h", "text" => "21号公寓",],
					["id" => "22h", "text" => "22号公寓",],
					["id" => "y1h", "text" => "研究生公寓南楼",],
					["id" => "y2h", "text" => "研究生公寓北楼",],
			],
			// east
			"east" => [
					["id" => "d1h", "text" => "东区1号公寓",],
					["id" => "d2h", "text" => "东区2号公寓",],
					["id" => "d4h", "text" => "东区4号公寓",],
					["id" => "d6h", "text" => "东区6号公寓",],
					["id" => "d8h", "text" => "东区8号公寓",],
					["id" => "d9h", "text" => "东区9号公寓",],
					["id" => "d10h", "text" => "东区10号公寓",],
			],
	];
	
	public function index() {
		$request = Request::instance();
		$logs = model('logs');
		
		$_ip = $request->ip();
		$_url = $request->url(true);
		
		$logs->log($_ip, 0, 'energy', $_url, '/energy');
		
		$m_alert = model('alert');
		$m_button = model('button');
		$alert = $m_alert->getAlert('energy');
		$button = $m_button->getButton('energy');
		
		$this->assign([
				'alert' => $alert,
				'button' => $button,
				'building' => Energy::$build_list,
				'title' => '学生用电查询',
				'footer_extern_link' => [
						'href' => 'http://api.dogest.cn/grade/energy.html',
						'title' => 'API文档',
				],
				'footer_extern_link2' => [
						'href' => 'https://github.com/MeiK-h',
						'title' => 'The original API provided by MeiK',
						'icon' => 'glyphicon glyphicon-thumbs-up',
				],
				'footer_extern_context' => [
						
				],
		]);
		
		return $this->fetch();
	}
	
	public function item() {
		$request = Request::instance();
		if($request->isAjax()) {
			$_id = $request->param('id','');
			$_room = $request->param('room','');
			if(empty($_id) || empty($_room)) {
				$this->error('参数有误');
			}
			
			$j_data = null;
			$url='http://api.dogest.cn/grade/energy/query?id='.$_id.'&room='.$_room;
			$html = file_get_contents($url);
			$j_data = json_decode($html);
			
			$code = $j_data->code;
			$message = $j_data->message;
			$status = $j_data->status;
			$data = null;
			$_name = "no_room";
			$_remain = "0";
			$_l_color = 'success';
			if($code == 0) {
				$data = $j_data->data;
				
				//$data->blackout = 1;
				//$data->lastreading = 1.01;
				//$data->ilegalblackout = 1;
				
				$_name = $data->userName;
				if(floatval($data->lastreading) > 20) {
					$_l_color = 'success';
				} else if(floatval($data->lastreading) > 1) {
					$_l_color = 'warning';
				} else {
					$_l_color = 'danger';
				}
				
				$_lr = floatval($data->lastreading);
				
				$_max_u = floatval($data->monthaverage);
				$_min_u = floatval($data->weekaverage);
				$_left = floor($_lr / (empty($_max_u) ? 1 : $_max_u));
				$_right = floor($_lr / (empty($_min_u) ? 1 : $_min_u));
				
				$_remain = $_left.'~'.$_right;
				if(empty($_max_u)) {
					$_remain = '好多好多';
				}
			}
			
			$logs = model('logs');
			$_ip = $request->ip();
			$_url = $request->url(true);
			
			$logs->log($_ip, 6, $_id.'['.$_name.']['.$_room.']', $_url, 'id='.$_id.'&room='.$_room);
			
			$this->assign([
					'data' => $data,
					'code' => $code,
					'message' => $message,
					'status' => $status,
					'remain' => $_remain,
					'l_color' => $_l_color,
			]);
			
			return $this->fetch();
			
			
		} else {
			$this->error();
		}
	}
	
}