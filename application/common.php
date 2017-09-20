<?php


// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 获取分值对应的情景类
 * @param unknown $grade
 */
function color_class($grade) {
	if(is_numeric($grade)) {
		if(floatval($grade) >= 60) {
			return "success";
		}
		return "danger";
	} else {
		if($grade=="免修") {
			return "info";
		} else if($grade == "不及格" || $grade == "不合格" || $grade == "缺考" || $grade == "休学" || $grade == "未选" || $grade == "-") {
			return "danger";
		} else if($grade == "缓考") {
			return "warning";
		}
		return "success";
	}
}
/**
 * 过滤特殊字符
 * @param unknown $str
 * @return string
 */
function htmlFilter($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * 获取公寓号
 * @param unknown $str
 */
function getDormitoryId($str) {
	return strchr($str, 'H', true);
}

/**
 * 获取房间号
 * @param string $room
 */
function getRoomId($room) {
	$r_id = substr(strchr($room, 'H'), 1);
	return $r_id.'室';
}

/**
 * 获取公寓、房间号完整名称
 * @param string $room
 */
function getRoomName($room) {
	$key = strchr($room, 'H', true);
	$r_id = substr(strchr($room, 'H'), 1);
	$r_name = config('room_name.'.$key);
	return $r_name.$r_id.'室';
}

/**
 * 生成json响应字符串（Ajax）
 * @param string $msg 传输的消息
 * @param string $ok 请求是否完成
 * @return \think\response\Json json字符串
 */
function getAjaxResp($msg="error", $ok=false, $code=0) {
	return json([
			"success" => $ok,
			"msg" => $msg,
			"code" => $code,
	]);
}

/**
 * 检查对象并设置默认值
 * @param mixed $object 要检查的对象
 * @param mixed $def 默认值
 * @return mixed 对象非空则返回对象，否则返回默认值
 */
function ifdefault($object, $def = null) {
	return isset($object) ? $object : $def;
}
/**
 * 获取Unix时间戳秒数
 * @return int
 */
function timestamp() {
	list($msec, $sec) = explode(' ', microtime());
	$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
	return $msectime;
}
/**
 * 将整数与-,分隔符表示的区间转换成二进制位标记的十进制形式，如1-2,4-6表示成二进制1110110，十进制118
 * @param string $str 要转换的字符串，只包含整数、分隔符','，区间符'-'
 * @return int 返回十进制整数
 */
function intervalToInteger($str) {
	$arr = explode(',', $str);
	$ret = 0;
	foreach ($arr as $item) {
		$bet = explode('-', $item);
		if(count($bet) == 1) {
			$ret = $ret | (1 << intval($bet[0]));
		} else {
			$n = intval($bet[1]);
			for($i = intval($bet[0]); $i <= $n; $i++) {
				$ret = $ret | (1 << $i);
			}
		}
	}
	return $ret;
}
/**
 * 将整数转换成区间表示，整数二进制位1代表可用区间
 * @param int $num
 * @return string 转换后的字符串
 */
function integerToInterval($num) {
	$x = 0;
	$ret = [];
	$lft = -1;
	while($num > 0) {
		$tp = $num & 1;
		if($tp == 1 && $lft == -1) {
			$lft = $x;
		} else if($tp == 0 && $lft != -1) {
			if($x -1 == $lft) {
				array_push($ret, (string)$lft);
			} else {					
				array_push($ret, $lft.'-'.($x - 1));
			}
			$lft = -1;
		}
		$x++;
		$num = $num >> 1;
	}
	if($lft != -1) {
		if($x -1 == $lft) {
			array_push($ret, (string)$lft);
		} else {					
			array_push($ret, $lft.'-'.($x - 1));
		}
	}
	$str = '';
	foreach ($ret as $it) {
		if($str != '') {
			$str .= ',';
		}
		$str .= $it;
	}
	return $str;
}