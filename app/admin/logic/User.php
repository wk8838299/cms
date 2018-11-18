<?php
namespace app\admin\logic;
use think\Db;
use think\Request;
use think\Config;
use app\admin\model\AuthGroup;
use app\admin\model\User as Member;

class User 
{
	public function authUserAdd($uid, $gid)
	{
		$result =[];
		$result['status'] = 0;
		if($res = Db::name('AuthGroupAccess')->where('uid', $uid)->find()) 
		{
			if($res['group_id'] == $gid)
			{
				$result['msg'] = '已经存在,请勿重复添加';
				return $result;
			} else {
				$res1 = Db::name('AuthGroup')->where('id', $res['group_id'])->find();
				if(!$res1){
					$result['msg'] = '当前组不存在';
				}
				$result['msg'] = '已经存在[' . $res1['title'] . ']组,不可重复添加';
				return $result;
			}
		}

		$model = new AuthGroup;
		if(is_numeric($uid)){
			if (is_administrator($uid)) {
				$result['msg'] = '该用户为超级管理员';
				return $result;
			}
			if(!Db::name('admin')->where('id',$uid)->find()) 
			{
				$result['msg'] = '管理员用户不存在';
				return $result;
			}
			
		}
		

		if ($model->addToGroup($uid, $gid)) {
			$result['msg'] = '操作成功';
			$result['status'] = 1;
			return $result;
		}
		else {
			$result['msg'] = '操作失败';
		}
		return $result;
	}

	public function getMemberList($request){
		$field = $request->get('field');
		$name = $request->get('name');
		$status =(int) $request->get('status');
		$condition = [];
		
		if($field && $name){
			switch ($field) {
				case 'realname':
					$condition['i.realname'] = $name;
					break;
				case 'mobile':
					$condition['u.mobile'] = $name;
					break;
				case 'icard':
					$condition['i.icard'] = $name;
					break;
				case 'username':
					$condition['u.username'] = $name;
					break;
				default:
					# code...
					break;
			}
		}
		if($status){
			$condition['u.status'] = $status-1;
		}
		$model = new Member;
		$list = $model->getUserList($condition);
		return $list;
	}
}