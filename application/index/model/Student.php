<?php
namespace app\index\model;

use think\Model;
use app\common\MyException;

class Student extends Model {
	
	protected $pk = 'id';
	protected $table = 'info';
	
	protected function initialize() {
		parent::initialize();
			
	}
	
	public function getStudentByName($name) {
		if(empty($name)) {
			return null;
		}
		try {
			// "select * from info where name=".$name;
			return $this->where('name', $name)->select();
		} catch (\think\Exception $e) {
			throw $e;
		}
	}
	
	public function getRoommate($room) {
		if(empty($room)) {
			throw new MyException('invalid param', -2);
		}
		try {
			// select * from info where dormitory
			return $this->where('dormitory', $room)->order('bed', 'asc')->select();
		} catch (\think\Exception $e) {
			throw $e;
		}
	}
	
}