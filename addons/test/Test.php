<?php
namespace addons\Test;
use think\Addons;

class Test extends Addons 
{
	public $info = [
		'name'  => 'test',
		'title' => '插件测试',
		'description' => 'thinkphp5插件测试',
		'status' => 0,
		'author' => 'fan',
		'version' => '0.1',
		'admin'  => '0',//是否有管理页面
	];

	public $admin_actions = [
        'index'=>[],//管理首页
        'config'=>['Admin/config'],//设置页
        'edit' => [],//编辑页
        'add'=>[],//增加页
    ];

    /**
     * @var array 插件钩子
     */
    public $hooks = [
        // 钩子名称 => 钩子说明
        'getinfo'=>'测试',
        'testhook' => '框架信息钩子'
    ];

	public function install()
	{
		return true;
	}

	public function uninstall()
	{
		return true;
	}
	public function abc(){
		return $this->fetch('abc');
	}
	public function testhook($param){
		return $this->fetch('info');
	}
}