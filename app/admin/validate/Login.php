<?php
namespace app\admin\validate;
use think\Validate;

class Login extends Validate
{
	protected $rule = [
		'username'	=>	'require|max:20',
		'password'	=>	'require',
	];
	protected $message = [
		'username.require'	=>	'用户名不能为空',
		'username.max'	=>	'用户名最多不能超过20个字符',
		'password.require' => '密码不能为空',
	];
	
}