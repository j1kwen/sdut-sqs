<?php
namespace app\index\model;

use think\Model;

class Logs extends Model {
	
	protected $pk = 'id';
	protected $table = 'logs';
	
	protected function initialize() {
		parent::initialize();
	}
	
	public function log($ip, $type, $content, $url, $orig) {
		$sql = "INSERT INTO logs (ip,type,content,orig_url,orig_param,dt) VALUES ('"
				.$ip."',".$type.",'".htmlFilter($content)."','".htmlFilter($url)."','".htmlFilter($orig)."',NOW())";
// 		$sql = "INSERT INTO logs (ip,type,content,orig_url,orig_param,dt) VALUES"
// 				." ('?',?,'?','?','?',NOW())";
// 		return $this->execute($sql, [$ip, $type, $content, $url, $orig]);
		return $this->execute($sql);
	}
	
	public function item($type, $page, $size) {
		$start = ($page-1) * $size;
		$sql = "SELECT * FROM logs WHERE type=".$type." ORDER BY id DESC LIMIT ".$start.",".$size;
		return $this->query($sql);
	}
	
	public function getCount() {
		$sql = "SELECT COUNT(id) AS val,type FROM logs GROUP BY type ORDER BY type";
		return $this->query($sql);
	}
	
}