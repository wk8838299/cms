<?php
namespace app\admin\controller;

class Article extends Admin 
{
	public function index(){
		return $this->fetch('index');
	}

	public function edit()
	{
		return $this->fetch('edit');
	}
}