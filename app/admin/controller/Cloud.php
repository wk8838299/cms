<?php
namespace app\admin\controller;

class Cloud extends Admin 
{
	public function index()
	{
		return $this->fetch('index');
	}
}