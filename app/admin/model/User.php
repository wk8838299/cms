<?php
namespace app\admin\model;

use think\Model;
use think\Db;
use think\Config;

class User extends Model
{
	public function getAuthUserList($group_id)
	{
		$prefix = Config::get('database.prefix');
		$auth_group_access = $prefix . 'auth_group_access';
		$admin = $prefix . 'admin';
		$data = $this->table($auth_group_access .' a')
		->field('m.id,m.username,m.nickname,m.last_login_time,m.last_login_ip,m.status')
		->join($admin . ' m','m.id=a.uid')
		->where("a.group_id = '".$group_id."'")
		->order('a.uid desc')->paginate(10);
		return $data;
	}
	/**
	 * 获取前台用户列表
	 * @param  array $condition 查询条件
	 * @return object            查询结果集
	 */
	public function getUserList($condition){
		$prefix = config('database.prefix');
		$user_info = $prefix . 'user_info i';
		return $this->alias('u')->join($user_info,'i.uid=u.userid')->where($condition)->order('u.userid asc')->paginate(10);
	}

	public function getOne($userid)
	{
		$prefix = config('database.prefix');
		$user_info = $prefix . 'user_info i';
		return $this->alias('u')->join($user_info,'i.uid=u.userid')->where('u.userid',$userid)->find();
	}
}