<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class AuthGroup extends Model
{
	public function getGroupList()
	{
		$condition['module'] = 'admin';
		$list = Db::name('AuthGroup')->order('id asc')->where($condition)->paginate(10);
		return $list;
	}

	public function addToGroup($uid, $gid)
	{
		$rs = Db::name('AuthGroupAccess')->insert(['uid' =>$uid, 'group_id'=>$gid]);
		if($rs){
			return true;
		}else{
			return false;
		}
	}

	public function removeFromGroup($uid, $gid)
	{
		return Db::name('AuthGroupAccess')->where(array('uid' => $uid, 'group_id' => $gid))->delete();
	}
}
