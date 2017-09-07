<?php
namespace app\index\model;
use think\Model;
use think\Session;
class Auth extends Model {
	
	protected $pk = 'user';
	protected $table = 'admin';
	
	protected function initialize() {
		parent::initialize();
	}
	
	/**
	 * 验证用户名密码，并写入登录ip
	 * @param string $user
	 * @param string $pwd
	 * @param string $ip
	 * @return bool
	 */
	public function authUser($user, $pwd, $ip='127.0.0.1') {
		$db_user = $this->where('user', $user)->find();
		if(empty($db_user) || ($db_user['password'] != $pwd)) {
			return false;
		}
		$this->where('user', $user)->update([
				'last_login_at' => time(),
				'last_ip' => $ip,
		]);
		return true;
	}
	
	/**
	 * 修改密码
	 * @param string $user
	 * @param string $old
	 * @param string $pwd
	 * @return bool
	 */
	public function modifyPwd($user, $old, $pwd) {
		$db_user = $this->where('user', $user)->find();
		if(empty($db_user) || ($db_user['password'] != $old)) {
			return false;
		}
		$this->where('user', $user)->update([
				'password' => $pwd,
		]);
		return true;
	}
	
	/**
	 * 获取用户昵称
	 * @param string $user
	 * @return string|null
	 */
	public function getName($user) {
		$db_user = $this->where('user', $user)->find();
		if(empty($db_user)) {
			return null;
		}
		return $db_user['name'];
	}
	
	/**
	 * session登录用户或查询用户是否登录
	 * @param string $user
	 * @param string $name
	 * @return bool|void
	 */
	public static function login($user = null, $name = null) {
		if(empty($user)) {
			return Session::has('user');
		}
		if(empty($name)) {
			$auth = new Auth();
			$name = $auth->getName($user);
		}
		Session::set('user', $user);
		Session::set('name', $name);
	}
	
	/**
	 * 注销
	 */
	public static function logout() {
		Session::clear();
	}
}