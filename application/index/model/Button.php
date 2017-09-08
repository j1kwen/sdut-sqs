<?php
namespace app\index\model;

use think\Model;

class Button extends Model {
	
	protected $pk = 'model';
	protected $table = 'button';
	
	protected function initialize() {
		parent::initialize();
	}
	
	public function getButton($mod) {
		try {
			if(empty($mod)) {
				return null;
			}
			$_where = [
					'model' => $mod,
			];
			return $this->where($_where)->find();
		} catch (\think\Exception $e) {
			throw $e;
		}
	}
	
	public function modifyButton($mod, $text, $dis_text, $color, $enable) {
		try {
 			return $this->update([
					'text' => $text,
					'dis_text' => $dis_text,
					'color' => $color,
					'enable' => intval($enable),
			],[
					'model' => $mod,
			]);
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