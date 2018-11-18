<?php
namespace app\admin\validate;
use think\Validate;

class User extends Validate {
	protected $regex = ['mobile' => '/^(((1[0-9][0-9]{1})|159|153)+\\d{8})$/'];
	protected $rule = [
		'username' => 'require|length:5,25',
		'mobile'   => 'require|regex:mobile',
	];

	protected $message = [
		'username.require' => '用户名必须填写',
		'username.length' => '用户名长度5-25之间',
		'mobile.require'  => '手机号码必须填写',
		'mobile.regex'	 => '请输入正确的手机号码'
	];
}