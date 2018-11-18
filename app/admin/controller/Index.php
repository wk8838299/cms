<?php
namespace app\admin\controller;

class Index extends Admin
{
    public function index()
    {
    	$menu = model('Menu','logic');
    	//var_dump($menu->getMenus());
    	$list = [
    		['id' =>'1','title'=>'测试新闻标题','content'=>'测试手册测试'],
    		['id' =>'2','title'=>'测试新闻标题','content'=>'测试手册测试'],
    		['id' =>'3','title'=>'测试新闻标题','content'=>'测试手册测试'],
    	];
    	$this->assign('title','测试页面');
    	$this->assign('list',$list);
        return $this->fetch('index');
    }
    public function test(){
    	return $this->fetch('test');
    }
}
