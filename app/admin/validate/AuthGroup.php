<?php
namespace app\admin\validate;
use think\Validate;

class AuthGroup extends Validate 
{
	protected $rule = [
		'title' => 'require|max:50',
		'description' => 'require',
	];

	protected $message = [
		'title.require' => '用户组名称必须填写',
		'title.max' => '用户组名称不能超过25个字符',
		'description.require' => '描述必须填写',
	];
}