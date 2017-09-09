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
			];
			if($all == 0) {
				$_where['enable'] = 1;
			}
			return $this->where($_where)->order('idx')->select();
		} catch (\think\Exception $e) {
			throw $e;
		}
	}
	
	public function modifyAlert($id, $content, $icon, $color, $close, $enable) {
		try {
			return $this->update([
					'content' => $content,
					'icon' => $icon,
					'color' => $color,
					'close' => intval($close),
					'enable' => intval($enable),
			],[
					'id' => $id,
			]);
		} catch (\think\Exception $e) {
			throw $e;
		}
	}
}