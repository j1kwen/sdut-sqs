<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

class Grade extends BaseController {
	
	
	public function index() {
		$request = Request::instance();
		$logs = model('logs');
		 
		$_ip = $request->ip();
		$_url = $request->url(true);
		 
		$logs->log($_ip, 0, 'grade', $_url, '/grade');
		
		$m_alert = model('alert');
		$m_button = model('button');
		$alert = $m_alert->getAlert('grade');
		$button = $m_button->getButton('grade');
		
		$logo = $request->param('logo','');
		
		$this->assign([
				'alert' => $alert,
				'button' => $button,
				'title' => '山东理工大学绩点查询系统',
				'footer_extra_link' => [
						'href' => 'http://api.dogest.cn/grade/index.html',
						'title' => 'API文档',
				],
				'footer_extra_context' => [
						
				],
				'logo' => $logo,
		]);
		
		return $this->fetch();
	}
	
	public function item() {
		$request = Request::instance();
		if($request->isAjax()) {
			$_id = $request->param('id','');
			if(empty($_id)) {
				$this->error('参数有误');
			}
			
			$j_data = null;
			$url='http://api.dogest.cn/grade/grade/query?id='.$_id;
			$html = file_get_contents($url);
			$j_data = json_decode($html);
			
			$code = $j_data->code;
			$message = $j_data->message;
			$status = $j_data->status;
			$data = null;
			$has_minor = false;
			$rep2 = 0;
			$rep = 0;
			$_name = "n/a";
			$_grade = 0;
			if($code == 0) {
				$data = $j_data->data;
				$grade = $j_data->data->major->grade;
				$rep = $grade >= 70 ? 0 : ($grade >= 60 ? 1 : 2);
				$grade2 = $j_data->data->minor->grade;
				$rep2 = $grade2 >= 70 ? 0 : ($grade2 >= 60 ? 1 : 2);
				if(!empty($data->minor->list)) {
					$has_minor = true;
				}
				$_name = $data->student->name;
				$_grade = round($data->major->grade, 1);
			}
			
			$logs = model('logs');
			$_ip = $request->ip();
			$_url = $request->url(true);
				
			$logs->log($_ip, 5, $_id.'['.$_name.']['.$_grade.']', $_url, 'id='.$_id);
			
			$this->assign([
					'data' => $data,
					'code' => $code,
					'message' => $message,
					'status' => $status,
					'color_rep' => ['success', 'warning', 'danger'],
					'color_alert' => ['green', 'orange', 'red'],
					'message_rep' => [
							'恭喜你，可以顺利拿到毕业证和学位证~',
							'很遗憾，你只能拿到毕业证哦~',
							'很抱歉，你的绩点无法毕业！',
					],
					'icon_rep' => [
							'glyphicon-ok-circle',
							'glyphicon glyphicon-ban-circle',
							'glyphicon-remove-circle',
					],
					'rep' => $rep,
					'rep2' => $rep2,
					'has_minor' => $has_minor,
					'chk_set' => [],
					'chk_set2' => [],
			]);
			
			return $this->fetch();
			
			
		} else {
			$this->error();
		}
	}
}