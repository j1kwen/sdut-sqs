<?php
namespace app\index\model;

use think\Model;

class Alert extends Model {
	
	protected $pk = 'id';
	protected $table = 'alert';
	
	protected function initialize() {
		parent::initialize();
	}
	
	public function getAlert($mod, $all = 0) {
		try {
			if(empty($mod)) {
				return null;
			}
			// "select * from info where name=".$name;
			$_where = [
				'model' => $mod,
				'type' => 0,
			];
			if($all == 0) {
				$_where['enable'] = 1;
			}
			return $this->where($_where)->order('idx')->select();
		} catch (\think\Exception $e) {
			throw $e;
		}
	}
	
	public function getButtonStatus($mod, $all = 0) {
		try {
			if(empty($mod)) {
				return null;
			}
			$_where = [
					'model' => $mod,
					'type' => 1,
			];
			if($all == 0) {
				$_where['enable'] = 1;
			}
			return $this->where($_where)->find();
		} catch (\think\Exception $e) {
			throw $e;
		}
	}
	
	public function getModel() {
		try {
			$sql = "select distinct model from ".$this->table;
			return $this->query($sql);
		} catch (\think\Exception $e) {
			throw $e;
		}
	}
}