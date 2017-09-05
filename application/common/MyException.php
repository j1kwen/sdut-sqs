<?php

namespace app\common;

class MyException extends \Exception {
	
	protected $data = [];
	
	public function __construct($msg = null, $code = 0) {
		if(!empty($msg)) {
			$this->data['msg'] = $msg;
		}
		$this->data['code'] = $code;
	}
	
	public function getData($field = '') {
		if(empty($field)) {			
			return $this->data;
		}
		return $this->data[$field];
	}
}